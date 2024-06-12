
# About

This library allows you to quickly create the post signed request for S3, from the database models.


## First Step

- Includes trait **S3PostSigned** in your model

```php
use S3PostSigned;
```


## Here's an example of using `createPostSigned` method:

```php
$post = new Post();
$sizeBytes = 80000;
$bucketName = 'example';
$key = 'xyz/abc/horse.mp3';
$postSigned = $post->createPostSigned($bucketName, $key, $sizeBytes);
