
## Steps to configuration

1. Create new ".env" file and copy the content from ".env.template" and paste it into ".env" file
2. Run "composer install"
3. Run "npm install & npm run dev"
4. Import both the databases with same name as "demo_wosulerp" and "wosulerp_admin"
5. Run "php artisan storage:link"
6. Add a virtual host for "demo.wosulerp.test" and "wosulerp.test"

Now, open the URL "demo.wosulerp.test" in browser and check!