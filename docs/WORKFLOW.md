# Git Workflow

## Environments

Production:
- Path: `/home/thakfu/git/ptf`
- Branch: `main`
- Database: production DB

Stage:
- Path: `/var/www/stage.ptf`
- Branch: `stage`
- Database: staging DB

## Session Startup Checklist

Run this before editing:

```bash
pwd
git status
git branch --show-current

Expected for development:

/var/www/stage.ptf
On branch stage
stage
Development Rules
Do normal development in /var/www/stage.ptf.
Avoid direct production edits.
Commit work to stage.
Push stage to GitHub.
Merge to main only after testing.
Common Commands
git status
git add .
git commit -m "Describe the change"
git push origin stage
