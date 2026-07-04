# PTF 2.0 Roadmap

> Goal:
> Build a football league management platform that is easier to maintain, safer to operate, and eventually reusable for other leagues.

---

# Phase 1 - Foundation (Current)

## Infrastructure
- [x] GitHub repository
- [x] Production environment identified
- [x] Stage environment identified
- [x] Separate production/stage databases
- [ ] Finalize Git workflow
- [ ] Deployment documentation

## Documentation
- [x] DEVLOG
- [x] ROADMAP
- [x] GIT_WORKFLOW
- [x] DEPLOYMENT

Still Needed
- [ ] ARCHITECTURE.md
- [ ] DATABASE.md
- [ ] IMPORT_PIPELINE.md
- [ ] ADMIN_TOOLS.md

---

# Phase 2 - Stability

Goal:
Reduce weekly maintenance and eliminate fragile processes.

## Import Pipeline
- [ ] Review dataimport.php
- [ ] Map every export file
- [ ] Add import logging
- [ ] Add preflight validation
- [ ] Validate column lengths
- [ ] Validate required columns
- [ ] Transaction support
- [ ] Better error reporting
- [ ] Replace manual 1500-row workaround

## Season Management
- [ ] Remove hardcoded years
- [ ] Reduce manual season rollover
- [ ] Automate league week transitions
- [ ] Create season initialization process

## Security
- [ ] Remove Discord webhooks from Git
- [ ] Move secrets into server config
- [ ] Rotate all webhook URLs
- [ ] Audit sensitive files
- [ ] Review file permissions

---

# Phase 3 - Architecture

Goal:
Reduce technical debt.

## Service Layer
- [ ] Review existing service classes
- [ ] Build true service layer
- [ ] Move business logic out of pages
- [ ] Standardize database access

## Code Cleanup
- [ ] Remove duplicated SQL
- [ ] Remove duplicated PHP
- [ ] Standardize helper functions
- [ ] Improve folder organization

## Database
- [ ] Review schema
- [ ] Normalize where appropriate
- [ ] Document tables
- [ ] Review indexes
- [ ] Improve backup strategy

---

# Phase 4 - League Operations

Goal:
Make commissioner life easier.

## Dashboard
- [ ] One-click weekly wrap-up
- [ ] One-click week advance
- [ ] Automated module switching
- [ ] Better league status display

## Admin Tools
- [ ] Audit existing tools
- [ ] Improve workflows
- [ ] Add undo functionality
- [ ] Trade reversal tool
- [ ] Better transaction history
- [ ] Better logging

## Salary Cap
- [ ] Automatic cap enforcement
- [ ] Prevent illegal transactions
- [ ] Commissioner override
- [ ] Better reporting

## Free Agency
- [ ] Improve transparency
- [ ] Improve admin interface
- [ ] Reduce manual work

---

# Phase 5 - Reliability

Goal:
Reduce dependence on one person.

## Documentation
- [ ] Weekly operating guide
- [ ] Offseason guide
- [ ] Emergency procedures
- [ ] Disaster recovery

## Permissions
- [ ] Helper accounts
- [ ] Role-based permissions
- [ ] Admin audit log

## Automation
- [ ] Scheduled backups
- [ ] Import verification
- [ ] Health dashboard
- [ ] Alerting

---

# Phase 6 - Analytics

Goal:
Understand the simulator.

## Research
- [ ] Attribute impact analysis
- [ ] Injury analysis
- [ ] Progression analysis
- [ ] AI coaching analysis
- [ ] Salary demand analysis
- [ ] Hidden simulator behavior

## Statistics
- [ ] Advanced metrics
- [ ] Historical dashboards
- [ ] Team analytics
- [ ] Player comparison tools

---

# Phase 7 - User Experience

Goal:
Modernize the site.

## UI
- [ ] Refresh layout
- [ ] Mobile improvements
- [ ] Dark mode
- [ ] Accessibility improvements

## League Experience
- [ ] Better player pages
- [ ] Better team pages
- [ ] Interactive stats
- [ ] Better search

---

# Phase 8 - Platform

Goal:
Turn PTF into a reusable framework.

## Multi-League Support
- [ ] Configurable league settings
- [ ] League installer
- [ ] League management panel
- [ ] Shared codebase

## Integrations
- [ ] Discord
- [ ] Email
- [ ] API
- [ ] Mobile notifications

---

# Guiding Principles

- Never develop directly on production.
- Document decisions.
- Prefer reusable systems.
- Improve every file you touch.
- Protect league history.
- Automate repetitive work.
- Build the platform, not just the current league.
- Reduce dependency on manual commissioner work.