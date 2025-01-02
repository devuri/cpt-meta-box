# Changelog

## [0.5.2](https://github.com/devuri/cpt-meta-box/compare/v0.5.1...v0.5.2) (2025-01-02)


### Features

* adds `MetaRegistry` ([03f9aae](https://github.com/devuri/cpt-meta-box/commit/03f9aaed72354137cd7e6418a37a5d631e54ed7f))

## [0.5.1](https://github.com/devuri/cpt-meta-box/compare/v0.5.0...v0.5.1) (2025-01-01)


### Bug Fixes

* add handling for `thumbnail` field ([0c160c4](https://github.com/devuri/cpt-meta-box/commit/0c160c4bb3caef18b8daa552d4ca2eaab17cd79b))
* better `addField` for multiple field types of the same name ([c469ae7](https://github.com/devuri/cpt-meta-box/commit/c469ae7066819cbcf95169d37697b2933ce3b812))
* image grid update ([46d2835](https://github.com/devuri/cpt-meta-box/commit/46d2835135e1ed0fd8855dc64309f56dcdf1b0ea))

## [0.5.0](https://github.com/devuri/cpt-meta-box/compare/v0.4.2...v0.5.0) (2024-12-31)


### ⚠ BREAKING CHANGES

* post type support with form context and autosave data
* refactored updates the metabox and settings class
* new cpt-meta update

### Features

* adds post type support ([ccd2700](https://github.com/devuri/cpt-meta-box/commit/ccd2700fbd43872b789400607ea383f35146a2fe))
* new cpt-meta update ([0cab02a](https://github.com/devuri/cpt-meta-box/commit/0cab02a54bdd82aa94e173a40f920698928d66cc))
* new example plugin ([4bd7d5e](https://github.com/devuri/cpt-meta-box/commit/4bd7d5ed7c3c53c10496767656b7aad76c41db91))
* post type support with form context and autosave data ([5a213cb](https://github.com/devuri/cpt-meta-box/commit/5a213cbff869bd05199faa4bb69fa4cca067a075))
* refactored updates the metabox and settings class ([4b0b2cb](https://github.com/devuri/cpt-meta-box/commit/4b0b2cb96b75f258f6128caef7bcc6dd51372501))


### Bug Fixes

* use `save_post_$posttype` ([711e8a9](https://github.com/devuri/cpt-meta-box/commit/711e8a9b00bbb313ae94d8ada37b4557e59cbc18))


### Miscellaneous Chores

* build ([916acbd](https://github.com/devuri/cpt-meta-box/commit/916acbd348938420b1fafae16126397822f56c3c))
* build ([3ba1299](https://github.com/devuri/cpt-meta-box/commit/3ba129947b51cb6b3f10379c252df88873bbbc69))

## [0.4.2](https://github.com/devuri/cpt-meta-box/compare/v0.4.1...v0.4.2) (2024-03-20)


### Bug Fixes

* fix empty array ([fbe3d32](https://github.com/devuri/cpt-meta-box/commit/fbe3d32a6428acd9815d295c4e3f0d252360fec4))

## [0.4.1](https://github.com/devuri/cpt-meta-box/compare/v0.4.0...v0.4.1) (2023-09-08)


### Bug Fixes

* update php req `^7.1 || ^7.4 || ^8.0 || ^8.1` ([81ffe60](https://github.com/devuri/cpt-meta-box/commit/81ffe60c2b34cbca7f2cf54c85379be09356cf80))

## [0.4.0](https://github.com/devuri/cpt-meta-box/compare/v0.3.1...v0.4.0) (2023-09-07)


### ⚠ BREAKING CHANGES

* namespace update `DevUri\Meta` is now `DevUri\PostTypeMeta`

### Features

* namespace update `DevUri\Meta` is now `DevUri\PostTypeMeta` ([1023c49](https://github.com/devuri/cpt-meta-box/commit/1023c49cb8d20a1c2c5f5c622717abd56dcf74de))

## [0.3.1](https://github.com/devuri/cpt-meta-box/compare/v0.3.0...v0.3.1) (2023-08-22)


### Bug Fixes

* update docs and `Data` class ([b32bd25](https://github.com/devuri/cpt-meta-box/commit/b32bd2593bf7efbf818dcb59b5fd3a29c9bd1baf))

## [0.3.0](https://github.com/devuri/cpt-meta-box/compare/v0.2.3...v0.3.0) (2023-08-21)


### ⚠ BREAKING CHANGES

* update build for metabox with new $post_object, removes $get_meta
* adds `create( $post_object, $meta_field )`
* adds post_object, in build with create to simplify setup

### Features

* Adds `action` `cptm_before_meta_update` `cptm_after_meta_update` ([028d997](https://github.com/devuri/cpt-meta-box/commit/028d99776c3ca2ed9856c5ce157fa0d0e48731e7))
* adds `create( $post_object, $meta_field )` ([bbb8426](https://github.com/devuri/cpt-meta-box/commit/bbb8426db1b3d929abfa968b275fce0a0b406808))
* adds `get_meta( string $key )` since we have $this-&gt;post_object ([3b4dbcb](https://github.com/devuri/cpt-meta-box/commit/3b4dbcb246cfb0789a6ab33ae1bc55804e21aaa1))
* adds better example code ([06b7d89](https://github.com/devuri/cpt-meta-box/commit/06b7d89596bdb63a40063f93155e5c58add90edb))
* adds better README.md ([b7b01dc](https://github.com/devuri/cpt-meta-box/commit/b7b01dcabc91c535252f6040bd99a8b1c47f7348))
* Adds build tools ([ab90532](https://github.com/devuri/cpt-meta-box/commit/ab905321aa01e6129ad31894f7be681ce50a4e0e))
* Adds custom prefix and group key `cpm-group-details_5791e5e6`, field suffix changed to `_cpm` example `movie_cpm` ([263d241](https://github.com/devuri/cpt-meta-box/commit/263d2410c4a0a096371410cef79a672ba61e6060))
* adds Data class ([8120e6d](https://github.com/devuri/cpt-meta-box/commit/8120e6de5eafbcb6cd3197ad849f3d610b4a4bcb))
* Adds Filter hook before save data ([19fa6f6](https://github.com/devuri/cpt-meta-box/commit/19fa6f685ace5cd7c2ec643e74da939438c628ea))
* adds MetaTrait ([4318e12](https://github.com/devuri/cpt-meta-box/commit/4318e1277bde77e5b4950717108a0a2a4b4c5bcb))
* adds optional meta name param. ([119d297](https://github.com/devuri/cpt-meta-box/commit/119d297ee93411a27d3059b8edf587c3d4db4ea7))
* adds post_object, in build with create to simplify setup ([5ddae98](https://github.com/devuri/cpt-meta-box/commit/5ddae988ac51fef0191ba72bf942cd5c9bd95859))
* adds StyleTrait ([5e20947](https://github.com/devuri/cpt-meta-box/commit/5e20947c170d3e91a1bd1eea8aea9e3578cdb520))
* update build for metabox with new $post_object, removes $get_meta ([9b369ed](https://github.com/devuri/cpt-meta-box/commit/9b369eda2ded3c03d8c2ed96cb3462c0e100a862))


### Bug Fixes

* fields is null by default ([1a43bb5](https://github.com/devuri/cpt-meta-box/commit/1a43bb50585202218708af2dc4eda0638578ba0a))
* fix typo ([75163cf](https://github.com/devuri/cpt-meta-box/commit/75163cfb87494f1ed19692ba5cf2ed19eac27542))
* fixes trait styles ([7430309](https://github.com/devuri/cpt-meta-box/commit/7430309bdf2874364efe75614e85a4406d26f0fb))
* update readme ([594b92b](https://github.com/devuri/cpt-meta-box/commit/594b92b425fd3aa875ccfbe2a59ddb9f09465826))


### Miscellaneous Chores

* build ([368688d](https://github.com/devuri/cpt-meta-box/commit/368688da26f6ffd343dbeb7ccd4aca1bb6ccd729))
