# Kodal Two-Factor Authentication Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).


## 1.1.0 - 2019-09-26
### Added
- update auth process to use file storage and cookie instead of user session to allow longer authentication sessions
    - store auth cookie with hash and with expiry date in browser
    - store hash and verify data in file storage

## 1.0.2 - 2019-09-25
### Added
- add `craft.email2fa.isVerified` variable to check if a user is verified in twig templates


## 1.0.1 - 2019-09-06
### Added
- update instruction text


## 1.0.0 - 2019-09-06
### Added
- Initial release