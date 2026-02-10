# MyfavMig

Migration Helper for Shopware 6


## Concept

* Provides a way to run single field migrations on entities.
* Provides a basic infrastructure. The detailed implementation has to be done in simple controllers.

## Configuration

* The plugin has a configuration. You need to provide a password, to be able to run the frontend commands. If you leave the field blank, the frontend workers will not be available. Configure it under ```Shopware Admin > Extensions > Plugins```.

## Example Processor

The example processor shows how to:

* Fetch a products from shopware 5 per SW5 API and update local product with same product number's data:
  * Manufacturer
  * Name and Description im default and additional language.
  * properties
  * custom fields
  * attached categories
  * state (active)