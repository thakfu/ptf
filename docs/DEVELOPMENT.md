# PTF Development Workflow

Production
- /var/www/ptf
- main branch
- Production DB

Stage
- /var/www/ptf-stage
- stage branch
- Stage DB

Services
Production
- /var/www/ptf-services

Stage
- /var/www/ptf-services-stage

Workflow

1. Develop on stage.
2. Commit to stage.
3. Push stage.
4. Test.
5. Merge stage → main.
6. Pull main on production.