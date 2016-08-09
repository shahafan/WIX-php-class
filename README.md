# WIX #

### simple usage ###
```php
<?php
class Widget
{
  $wix = new Wix();
  $instance = $wix->getDecodedInstance();
  if(!is_null($instance['vendorProductId'])){
    die('permission denied');
  }

  $this->instanceId = $instance['instanceId']; // unique for each website
  $this->compId = $wix->getCompIdFromRequest(); // unique for each widget
  $this->view->viewMode = $wix->getViewMode(); // editor / site
}
?>
```
