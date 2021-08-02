# s3-zip-objects

A package to handle S3 upload and download using PSR-7 interfaces, async operations and Zip download.

How does it work?
<br>
IZI
You only need to provide a set of AWS credentials to be able to do both uploading and downloading from resources, i.e:

1. your AWS IAM key;
2. your secret;
3. the bucket region;
4. the version used and

Then, it's required to instantiate any of the factories for downloading (S3DownloaderFactory, S3AsyncDownloaderFactory) or uploading (S3UploadingFactory, S3AsyncUploadingFactory), provide some aws object's info to the factories object's you received and voilá, you are able to use the functionalities provided by this lib.

## Downloading objects from S3

```
// Receive a stream from the objects
$stream = $streamResourceCollector->streamCollect(
  $bucketName,
  new ResourceObject('fookey.txt', 'foo-name.txt'),
  new ResourceObject('barkey.txt', 'bar-name.txt')
);


foreach ($resources as $objkey => $obj) {
    // do something
}

```

## Uploading objects to S3

```
// $source may be a object Stream or a string path
$results = $uploadObjects($bucketName, new UploadableObject('test-upload.txt', $source));

```

## Zip objects

```
  // $streamCollector must be an instance of StreamCollectorInterface
  $streamCollector = (new S3DownloaderFactory()->create(/* credentials */));
  $zip = new S3StreamObjectsZipDownloader($streamCollector);
  $streamOfZipFile = $zip->zipObjects(self::$bucket, [new ResourceObject('test.txt', 'testa.txt')]);

```
