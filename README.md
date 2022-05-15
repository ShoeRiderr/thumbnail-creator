## Create thumbnail

To create thumbnail in command prompt type:

```
    php artisan make:thumbnail path_to_image storage_destination
```

storage_destinantion is optional and is set to google cloud storage by default.

Images will also be stored locally in storage/images folder.

App is prepared for google cloud storage but there is possibility to add another storage.

To make program work you need to create account and new bucked in Google Cloud by this link https://console.cloud.google.com for example.
