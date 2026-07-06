#!/usr/bin/env bash
set -euo pipefail

REPO_DIR="${REPO_DIR:-/var/www/projects/codeigniter}"
BRANCH="${BRANCH:-master}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"

AUTO_IMPORT_ENABLED="${AUTO_IMPORT_ENABLED:-1}"
AUTO_IMPORT_ONLY_IF_EMPTY="${AUTO_IMPORT_ONLY_IF_EMPTY:-1}"
AUTO_IMPORT_MOVIES="${AUTO_IMPORT_MOVIES:-80}"
AUTO_IMPORT_SHOWS="${AUTO_IMPORT_SHOWS:-80}"

log() {
  printf '[%s] %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*"
}

ensure_clean_worktree() {
  if ! git diff --quiet || ! git diff --cached --quiet; then
    log "Aborted: git working tree has local changes."
    exit 2
  fi

  if [[ -n "$(git ls-files --others --exclude-standard)" ]]; then
    log "Aborted: git working tree has untracked files."
    exit 2
  fi
}

main() {
  cd "$REPO_DIR"

  if ! command -v git >/dev/null 2>&1; then
    log "Aborted: git is not installed."
    exit 2
  fi

  if ! command -v "$PHP_BIN" >/dev/null 2>&1; then
    log "Aborted: PHP binary '$PHP_BIN' is not available."
    exit 2
  fi

  ensure_clean_worktree

  log "Fetching latest commit from origin/$BRANCH..."
  git fetch origin "$BRANCH"

  local_sha="$(git rev-parse HEAD)"
  remote_sha="$(git rev-parse "origin/$BRANCH")"

  if [[ "$local_sha" != "$remote_sha" ]]; then
    log "Updating code from $local_sha to $remote_sha..."
    git pull --ff-only origin "$BRANCH"
    log "Git update complete."
  else
    log "No new commit found on origin/$BRANCH."
  fi

  if [[ -f composer.json ]] && command -v "$COMPOSER_BIN" >/dev/null 2>&1; then
    log "Installing composer dependencies..."
    "$COMPOSER_BIN" install --no-dev --no-interaction --prefer-dist --optimize-autoloader
  elif [[ -f composer.json ]]; then
    log "Composer not found; skipping dependency install."
  fi

  log "Ensuring writable folders exist..."
  mkdir -p writable/cache writable/logs writable/session writable/uploads writable/screens writable/database
  if [[ ! -f writable/database/project24.sqlite ]]; then
    touch writable/database/project24.sqlite
  fi

  log "Running database migrations..."
  "$PHP_BIN" spark migrate --all --no-header

  if [[ "$AUTO_IMPORT_ENABLED" == "1" ]]; then
    import_args=(--movies "$AUTO_IMPORT_MOVIES" --shows "$AUTO_IMPORT_SHOWS")
    if [[ "$AUTO_IMPORT_ONLY_IF_EMPTY" == "1" ]]; then
      import_args+=(--only-if-empty)
    fi

    log "Running catalog import command..."
    "$PHP_BIN" spark catalog:import-popular "${import_args[@]}"
  else
    log "AUTO_IMPORT_ENABLED=0, skipping catalog import."
  fi

  log "Sync completed successfully."
}

main "$@"
