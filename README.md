# Nova Rating-Card
Simple Laravel Nova Card. 

## Installation

You can install the package in to a Laravel app that uses Nova via composer:
```
composer require nova-cards/rating-card
```

## Usage:
```php
public function cards() {
    return [
	new RatingCard('Your Avg Rating', 10, 'App\Movie', 'rating'),
    ];
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
