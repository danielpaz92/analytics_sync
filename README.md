# Analytics Sync using Google Analytics Report API V4

First of all execute
composer update

Then, put your jSON File Key for Google API Inside a project folder
It must be renamed to service-account-credentials.json

Then composer dump-autoload

You can execute it in 2 ways
1 way: Open a browser and then fill out the form with
- View ID
- Start Date
- End Date

2 way: Open Analytics.php passing parameters
Analytics.php?view_id=[YOUR_VIEW_ID]&start_date=[START_DATE]&end_date=[END_DATE]

By default it brings the Behavior All pages Report, you can change the data which you want to grab in Analytics.php

This project uses Bootstrap 4, jQuery, DataTables.
