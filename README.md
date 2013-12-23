# Buffer PHP SDK

Unofficial Buffer SDK for PHP.

This package is compliant with [PSR-0][], [PSR-1][], and [PSR-2][]. If you
notice compliance oversights, please send a patch via pull request.

[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md

## Installation

Via [Composer](http://getcomposer.org):

``` json
{
    "require": {
        "ipalaus/buffer-php-sdk": "1.*"
    }
}
```

## Usage

First of all, you need a valid token to be able to send authorized requests to the Buffer API. You can [register your app](http://bufferapp.com/developers/apps) and use the provided access token or get a user's token [authenticating](http://bufferapp.com/developers/api/oauth) with OAuth 2.0. 

Once you have a valid token, you can create a new ```TokenAuthorization``` instance and then create a ```Client`` instance:

```php
use Ipalaus\Buffer\Client;
use Ipalaus\Buffer\TokenAuthorization;

$auth = new TokenAuthorization('access_token');

$client = new Client($auth);
```

## Methods

## User

### Get a user

Returns a single user.

```php
$user = $client->getUser();
```

## Profiles

### Get profiles

Returns an array of social media profiles connected to a users account.

```php
$profiles = $client->getProfiles();
```

### Get a single profile

Returns details of the single specified social media profile.

```php
$client->getProfile($id);
```

### Get posting schedules

Returns details of the posting schedules associated with a social media profile.

```php
$client->getProfileSchedules($id);
```

### Set posting schedules

Set the posting schedules for the specified social media profile.

```php
use Ipalaus\Buffer\Schedule;

$schedule = new Schedule;
$schedule->addDay('mon');

// you can pass a single string or an array
$schedule->addDay(array('tue', 'wed')); 

$schedule->addTime('09:00');

// same for time
$schedule->addTime(array('12:00', '15:00')); 

$client->updateProfileSchedules('id', $schedule);

// alternative syntax, even shorter
$schedule = new Schedule(array('mon', 'tue', 'wed'), array('09:00', '12:00', '15:00'));
$client->updateProfileSchedules($id, $schedule);

// multiple schedules
$weekdays = new Schedule(array('mon', 'tue', 'wed', 'thu', 'fri'), array('09:00', '12:00', '16:00');
$weekends = new Schedule(array('sat', 'sun'), array('12:00', '18:00');

$client->updateProfileSchedules($id, array($weekdays, $weekends));
```

**Note**: updating multiple schedules seems to be broken in the Buffer API. I'll email them and try to fix the issue. For now, you can only update one schedule.

## Updates

### Get a update

Returns a single social media update.

```php
$client->getUpdate('id');
```

### Get pending updates

Returns an array of updates that are currently in the buffer for an individual social media profile.

```php
$client->getProfilePendingUpdates('id');

// optional parameters
$client->getProfilePendingUpdates($id, $page = null, $count = null, $since = null, $utc = false)
```

- `$page integer` Specifies the page of status updates to receive. If not specified the first page of results will be returned.
- `$count integer` Specifies the number of status updates to receive. If provided, must be between 1 and 100.
- `$since integer` Specifies a unix timestamp which only status updates created after this time will be retrieved.
- `$utc boolean` If utc is set times will be returned relative to UTC rather than the users associated timezone.

### Get sent updates
 
Returns an array of updates that have been sent from the buffer for an individual social media profile.

```php
$client->getProfileSentUpdates($id);

// optional parameters
$client->getProfileSentUpdates($id, $page = null, $count = null, $since = null, $utc = false);
```
- `$page integer` Specifies the page of status updates to receive. If not specified the first page of results will be returned.
- `$count integer` Specifies the number of status updates to receive. If provided, must be between 1 and 100.
- `$since integer` Specifies a unix timestamp which only status updates created after this time will be retrieved.
- `$utc boolean` If utc is set times will be returned relative to UTC rather than the users associated timezone.

### Get update interactions

Returns the detailed information on individual interactions with the social media update such as favorites, retweets and likes.

```php
$client->getUpdateInteractions($id);

// optional parameters
$client->getUpdateInteractions($id, $page = null, $count = null, $event = null);
```

- `$page integer` Specifies the page of interactions to receive. If not specified the first page of results will be returned.
- `$count integer` Specifies the number of interactions to receive. If provided, must be between 1 and 100.
- `$event string` Specifies a type of event to be retrieved, for example "retweet", "favorite", "like", "comment", "mention" or "share". They can also be plural (e.g., "shares"). Plurality has no effect other than visual semantics.

### Reorder updates

Edit the order at which statuses for the specified social media profile will be sent out of the buffer.

```php
$client->reorderProfileUpdates($id, array($update1, $update3, $update5));

// optional parameters
$client->reorderProfileUpdates($id, $order, $offset = null, $utc = false)
```

- `$order array` An ordered array of status update id’s. This can be a partial array in combination with the offset parameter or a full array of every update in the profiles Buffer.
- `$offset integer` Specifies the number of status updates to receive. If provided, must be between 1 and 100.
- `$utc boolean` If utc is set times will be returned relative to UTC rather than the users associated timezone.

### Randomize updates order

Randomize the order at which statuses for the specified social media profile will be sent out of the buffer.

```php
$client->shuffleProfileUpdates($id);

// optional parameters
$client->shuffleProfileUpdates($id, $count = null, $utc = false);
```

- `$count integer` Specifies the number of status updates returned. These will correspond to the first status updates that will be posted.
- `$utc boolean` If utc is set times will be returned relative to UTC rather than the users associated timezone.

### Create a update

Create one or more new status updates.

```php
use Ipalaus\Buffer\Update;

$update = new Update;

$update->text = 'Check out my website!';
$update->addProfile($id);

$update->shorten = false; // optional, default: true 
$update->now = false; // optional, default: true
$update->top = true; // optonal, default: false

// adding media is optional, avaible options: link, description, image
$update->addMedia('link', 'http://ipalaus.com');
$update->addMedia('description', 'Isern Palaus personal website.');
$update->addMedia('image', 'http://ipalaus.com/img/isern-palaus_smile.jpg');

// schedule a update is optional 
$update->schedule(time() + 3600); // you can use timestamp
$update->schedule('2013-12-23 12:03:23'); // or a valid date/time string

$client->createUpdate($update);

```

- `Update::$text string` The status update text.
- **Profiles** Profile id’s that the status update should be sent to. Invalid profile_id’s will be silently ignored.
- `Update::$shorten boolean` If shorten is false links within the text will not be automatically shortened, otherwise they will.
- `Update::$now boolean` If now is set, this update will be sent immediately to all profiles instead of being added to the buffer.
- `Update::$top boolean` If top is set, this update will be added to the top of the buffer and will become the next update sent.
- **Media** Media to be attached to the update, currently accepts link, description and picture parameters.
- **Schedule** A date describing when the update should be posted. Overrides any top or now parameter.

### Update an update

Edit an existing, individual status update.

```php
$update = new Update;
$update->text = 'Lorem ipsum'; // required

$client->updateUpdate($id, $update);
```

- `Update::$text string` The status update text.
- `Update::$now boolean` If now is set, this update will be sent immediately to all profiles instead of being added to the buffer.
- **Media** Media to be attached to the update, currently accepts link, description and picture parameters.
- **Schedule** A date describing when the update should be posted. Overrides any top or now parameter.

### Share an update

Immediately shares a single pending update and recalculates times for updates remaining in the queue.

```php
$client->shareUpdate($id);
```

### Destroy an update

Permanently delete an existing status update.

```php
$client->destroyUpdate($id);
```

### 


- shareUpdate($id)
- destroyUpdate($id)
- moveUpdateToTop($id)
- getLinkShares($url)
- getConfigurationInfo()
