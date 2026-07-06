# Project24 Ubuntu Auto Sync Setup (GitHub -> Server)

Use these steps on your Ubuntu server in order.  
Do not move to the next step until the current one is complete.

## Step 1: Verify repo and remote access
```bash
cd /var/www/projects/codeigniter
git remote -v
git fetch origin master
git status -sb
```
Expected:
- `origin` points to `github.com:s00pin/project24_2`
- `git fetch` works with no auth error
- status is clean

## Step 2: Pull latest code from GitHub
```bash
cd /var/www/projects/codeigniter
git pull --ff-only origin master
```
Expected:
- `Already up to date` or a clean fast-forward pull

## Step 3: Make auto-sync script executable and test once
```bash
cd /var/www/projects/codeigniter
chmod +x scripts/server-sync-from-github.sh
bash scripts/server-sync-from-github.sh
```
Expected:
- fetch runs
- migrate runs
- catalog import command runs (or skips if already populated)
- ends with `Sync completed successfully.`

## Step 4: Install systemd service + timer
```bash
cd /var/www/projects/codeigniter
sudo cp ops/systemd/project24-autosync.service /etc/systemd/system/
sudo cp ops/systemd/project24-autosync.timer /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable --now project24-autosync.timer
```
Expected:
- timer enabled and started successfully

## Step 5: Verify timer and first run logs
```bash
systemctl status project24-autosync.timer --no-pager
systemctl list-timers project24-autosync.timer --all --no-pager
journalctl -u project24-autosync.service -n 80 --no-pager
```
Expected:
- timer shows next trigger time
- service log shows fetch/pull/migrate/import output

## Step 6: Optional tuning
You can edit runtime settings without changing repo files:

```bash
sudo systemctl edit project24-autosync.service
```

Example overrides:
```ini
[Service]
Environment=AUTO_IMPORT_MOVIES=120
Environment=AUTO_IMPORT_SHOWS=120
Environment=AUTO_IMPORT_ONLY_IF_EMPTY=0
```

Then apply:
```bash
sudo systemctl daemon-reload
sudo systemctl restart project24-autosync.timer
```

