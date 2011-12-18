# Postmark Email Gateway

- Version: 1.0
- Date: 2011-12-18
- Requirements: Symphony 2.2 or later
- Authors: Alistair Kearney, Michael Eichelsdörfer
- Maintainer: Symphony Community, <https://github.com/symphonists>

## Summary

Uses Symphony's core email API to send emails via Postmark (http://postmarkapp.com). It requires a Postmark account and API key.

Please note that at the time of writing Postmark does not allow bulk marketing emails.

## Installation

Information about [installing and updating extensions](http://symphony-cms.com/learn/tasks/view/install-an-extension/) can be found in the Symphony documentation at <http://symphony-cms.com/learn/>.

### Via Git

The extension includes Markus Hedlund's Postmark PHP class as a submodule. If installing via Git, be sure to do a resursive clone or initialize and update submodules after cloning.

## Usage

Once installed, go to System > Preferences and fill in your Postmark credentials. Under "Email Gateway" choose "Postmark" to use this extension as your default email gateway.

## Credits

This Symphony extension makes use of the Postmark PHP class by Markus Hedlund, which can be found here: <https://github.com/Znarkus/postmark-php>
