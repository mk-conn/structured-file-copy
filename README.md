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

```shell script
structure-copy copy:struct --target=/target/folder [--file-type=<file-type1,n>] [--source=/source/path] [--file-ext=<ext1,n>] [--sort=date:year] [--sort=date:month]
```

Not?
```shell script
~/.composer/vendor/bin/structure-copy copy:struct --target=/target/folder [--file-type=<file-type1,n>] [--source=/source/path] [--file-ext=<ext1,n>] [--sort=date:year] [--sort=date:month]
```

Get help:
```textmate
$ structure-copy copy:struct --help
Description:
  Copies files from a source folder to a target folder but in a sctructured way if you want.

Usage:
  copy:struct [options]

Options:
      --source[=SOURCE]              The from folder (if not set, files are taken from the folder where the command is running)
      --target=TARGET                Where to put the files
      --sort[=SORT]                  Sort in folders by (date:day,date:month,date:year,alpha:name) (multiple values allowed)
      --name-letters[=NAME-LETTERS]  By how many (first) letters should the name sorted
      --exclude-ext[=EXCLUDE-EXT]    Exclude files with extensions (multiple values allowed)
      --file-type[=FILE-TYPE]        Move only a file type [image, video, office, text, richtext, pdf] (multiple values allowed)
      --file-ext[=FILE-EXT]          Move only files with a specific extension (multiple values allowed)
  -h, --help                         Display this help message
  -q, --quiet                        Do not output any message
  -V, --version                      Display this application version
      --ansi                         Force ANSI output
      --no-ansi                      Disable ANSI output
  -n, --no-interaction               Do not ask any interactive question
  -v|vv|vvv, --verbose               Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```


## TBD

* Sort into date:day folders
* Sort into folders by filename (with user defined number of leading letters)
* Write tests