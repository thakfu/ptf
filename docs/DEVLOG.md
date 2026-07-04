 PTF Development Log

---
# Phase 0 - Preserve the League

Goal:
Keep PTF enjoyable to run.

Success means:

- Mike doesn't dread sim nights.
- Missing a weekend doesn't create a crisis.
- Trusted helpers can safely assist.
- The league survives even when life gets busy.

# SESSION 001 - 2026-07-03 - Project Kickoff / Infrastructure Cleanup

## Session Goals
- Reacquaint ourselves with the project.
- Verify Git workflow.
- Verify production and staging environments.
- Begin documenting the project for long-term development.

---

## Completed

### Git
- Cleaned production repository.
- Committed outstanding production code changes.
- Removed generated simulator files from the working tree.
- Production repository now has a clean working tree.

### Production
Location:
/home/thakfu/git/ptf

Branch:
main

Status:
- Clean
- Connected to GitHub
- Uses production database

### Stage
Location:
/var/www/stage.ptf

Branch:
stage

Status:
- Clean
- Connected to GitHub
- Uses separate staging database

### Documentation
Created:

docs/
    DEVLOG.md
    ROADMAP.md
    GIT_WORKFLOW.md
    DEPLOYMENT.md

---

## Discoveries

Production and staging are maintained as separate Git clones.

Production:
    /home/thakfu/git/ptf

Stage:
    /var/www/stage.ptf

Both connect to the same GitHub repository but operate on different branches.

This is the preferred workflow going forward.

---

## Workflow Going Forward

Development occurs only on:

/var/www/stage.ptf

Branch:

stage

When work is complete:

1. Commit to stage.
2. Push to GitHub.
3. Test.
4. Merge stage into main.
5. Pull main on production.

Avoid making direct edits on production whenever possible.

---

## Long-Term Goals

- Improve project architecture.
- Reduce technical debt.
- Build commissioner tools.
- Modernize UI where appropriate.
- Prepare the application to become a reusable platform for multiple football leagues.

---

## Ideas

Possible cleanup:
- Remove generated simulator exports from Git.
- Better organize import pipeline.
- Improve project documentation.
- Audit duplicate code.
- Create architecture documentation.

---

## Next Session

- Complete project documentation.
- Create Git workflow documentation.
- Begin a guided walkthrough of the application architecture.
- Map simulator -> import process -> database -> website.