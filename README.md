# s3-zip-objects

A package to handle s3 upload and download using PSR-7 interfaces, async operations and Zip download.

How does it work?
<br>
IZI
You only need to provide a set of AWS credentials to be able to do both uploading and downloading from resources, i.e:

1. your AWS IAM key;
2. your secret;
3. the bucket region;
4. the version used and
5. your bucket name.

Then, it's required to instantiate any of the factories for downloading (S3DownloaderFactory, S3AsyncDownloaderFactory) or uploading

## Download objects from aws
