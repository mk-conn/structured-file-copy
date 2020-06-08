# Copy and make it a structure

A little cli helper to copy files from one source folder into a target folder structure

## Why this in PHP and not just use given commandline linux/unix tools?

* Well... I like it like this: `structure-copy copy:struct --file-type=image --sort=date:year --sort=date:month` 
instead of `find -type | xargs... err what?.. err.. what was the next unix tool to pipe and filter files or types...? *sigh* Lets have a look on stack-overflow 😊`
* I primarly use it to copy photos and videos from my camera/phone into my local library which I have sorted by year and month.

## How to use

### Install 

`composer global require "mk-conn/structured-file-copy"`

Or into a custom place of your choice:

`git clone https://github.com/mk-conn/structured-file-copy.git`


### Usage

Composer is in your global path? Great! Just run

`structure-copy copy:struct --target=/target/folder [--file-type=<file-type1,n>] [--source=/source/path] [--file-ext=<ext1,n>] [--sort=date:year] [--sort=date:month]`

Not?

`~/.composer/vendor/bin/structure-copy copy:struct --target=/target/folder [--file-type=<file-type1,n>] [--source=/source/path] [--file-ext=<ext1,n>] [--sort=date:year] [--sort=date:month]`

## TBD

* Sort into date:day folders
* Sort into folders by filename (with user defined number of leading letters)
* Write tests