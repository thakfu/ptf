```md
# Deployment

## Current Known Setup

Production is served from:

```txt
/home/thakfu/git/ptf

Stage is served from:

/var/www/stage.ptf

Production and stage use separate databases.

Current Deployment Process

Current process is manual.

Planned flow:

Develop on stage
↓
Commit to stage
↓
Push stage
↓
Test on stage
↓
Merge stage into main
↓
Pull main on production
Production Update Checklist

Before updating production:

cd /home/thakfu/git/ptf
git status
git branch --show-current

Expected:

On branch main
main

Then update:

git pull origin main
Notes
Do not edit production directly unless it is an emergency.
Generated simulator exports should not drive deployment decisions.
Discord webhook secrets should be removed from Git and moved to server-side config.
Database changes need a documented backup/rollback process before major refactors.