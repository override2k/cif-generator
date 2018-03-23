[![Build Status](https://travis-ci.org/override2k/cif-generator.svg?branch=master)](https://travis-ci.org/override2k/cif-generator) 
[![codecov](https://codecov.io/gh/override2k/cif-generator/branch/master/graph/badge.svg)](https://codecov.io/gh/override2k/cif-generator)
[![StyleCI](https://styleci.io/repos/126259233/shield?branch=master)](https://styleci.io/repos/126259233)

# Spanish CIF number generator

This is a simple library for generating valid spanish CIF (VAT) numbers

## Installation

Install using composer

```sh
composer require overdesign/cif-generator
```

## Usage

You can generate CIF numbers in two ways:

1 - via static method

```php
<?php

use Overdesign\CifGenerator\Cif;

echo Cif::generate();
```

2 - via object instance

```php
<?php

use Overdesign\CifGenerator\Cif;

$cifGenerator = new Cif();

echo $cifGenerator->get();
```

