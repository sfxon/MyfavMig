# MyfavMig

Migration Helper for Shopware 6


## Concept

* Provides a way to run single field migrations on entities.
* Provides a basic infrastructure. The detailed implementation has to be done in simple controllers.

## Configuration

* The plugin has a configuration. You need to provide a password, to be able to run the frontend commands. If you leave the field blank, the frontend workers will not be available. Configure it under ```Shopware Admin > Extensions > Plugins```.