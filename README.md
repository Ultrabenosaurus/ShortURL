# ShortURL #

A simple class for generating your own short URLs.

## Notes ##

I've got a .htaccess rewrite rule for incoming short URLs, but I couldn't figure out the regex for incoming full URLs (to be shortened) so at the moment you have to use a query string (e.g. `?url=http://www.google.com/`) as I have done on the example index page through an AJAX call.

## Usage ##

**Basic**

Edit the database connection details in `url.class.php` to the appropriate values for your database, upload all four files to your server. Done.

**Advanced**

Edit the database connection details in `url.class.php` to the appropriate values for your database and upload it to your server. Create your own script to handle incoming requests however you want using the two public methods `add_url()` and `get_url()`. Simple.

**Example**

```php
include 'url.class.php';
$l = new URL($_SERVER['HTTP_HOST']);

if(isset($_GET['key']) && !empty($_GET['key'])){
  $link = $l->get_url($_GET['key']);
  if($link){
		if(preg_match('/http[s]*:\/\//', $link) < 1){
			$link = 'http://'.$link;
		}
		header("Location: ".$link);
	} else {
		header("Location: ./");
	}
}
if(isset($_GET['url']) && !empty($_GET['url'])){
	$key = $l->add_url(urldecode($_GET['url']));
	if($key){
		echo "<a href='".$key."'>".$key."</a>";
	} else {
		echo "URL could not be added.";
	}
}
```

## API ##

```php
add_url($url)
```

Create a short URL for the given `$url`. Generates a key, stores the URL and key in the database if it isn't already there. Returns the generated/found key (appeneded to your domain, if given when instantiating the class).

```php
get_url($key)
```

Ask the database for the full URL that matches the given key. If the key is found in the database, the corresponding URL is returned and a hit is registered for the key.

## License ##

As usual with my work, this project is available under the BSD 3-Clause license. In short, you can do whatever you want with this code as long as:

* I am always recognised as the original author.
* I am not used to advertise any derivative works without prior permission.
* You include a copy of said license and attribution with any and all redistributions of this code, including derivative works.

For more details, read the included [LICENSE.md](https://github.com/Ultrabenosaurus/ShortURL/blob/master/LICENSE.md) file or read about it on [opensource.org](http://opensource.org/licenses/BSD-3-Clause).
