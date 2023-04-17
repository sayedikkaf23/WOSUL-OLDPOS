
## Steps to configuration

1. Create new ".env" file and copy the content from ".env.template" and paste it into ".env" file
2. Run "composer install"
3. Run "npm install & npm run dev"
4. Import both the databases with same name as "demo_wosul" and "wosul_admin"
5. Run "php artisan storage:link"
6. Add a virtual host for "demo_wosul.test" and "wosul.test"

Now, open the URL "demo_wosul.test" in browser and check!