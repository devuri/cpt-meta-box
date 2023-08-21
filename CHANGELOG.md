# Changelog

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
