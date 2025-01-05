# Copy and make it a structure

A little cli helper to copy files from one source folder into a target folder structure

## Why this in PHP and not just use given commandline linux/unix tools?

* Well... I like it like this: `structure-copy copy:struct --file-type=image --sort=date:year --sort=date:month`
  instead of
  `find -type | xargs... err what?.. err.. what was the next unix tool to pipe and filter files or types...? *sigh* Lets have a look on stack-overflow 😊`
* I primarly use it to copy photos and videos from my camera/phone into my local library which I have sorted by year and
  month.

## How to use

### Install

`composer global require "mk-conn/structured-file-copy"`

Or into a custom place of your choice:

`git clone https://github.com/mk-conn/structured-file-copy.git`

### Usage

Composer is in your global path? Great! Just run

```shell script
sfc copy --source=/source/folder --target=/target/folder --sort=by:date:year,by:date:month --include=type:image --exclude=ext:jpg,ext:png
```

Not?

```shell script
~/.composer/vendor/bin/sfc copy --source=/source/folder --target=/target/folder --sort=by:date:year,by:date:month --include=type:image --exclude=ext:jpg,ext:png
```

Get help:

```textmate
$ sfc copy --help
Description:
  Copies files from a source folder to a target folder in a structured way

Usage:
  copy [options]

Options:
      --source[=SOURCE]        The source folder (if not set, files are taken from the folder where the command is executed)
      --target=TARGET          The target folder where the files will be copied
      --sort[=SORT]            Sort by strategies: [by:default|by:letter|by:date:year|by:date:month|by:date:day|by:file:type].
                               E.g., if you want to sort by date (year) and then by by letter, you can use: --sort-by=by:date:year,by:letter.
                               This will create a folder for each year and then a folder for each letter.
                                [default: "by:default"]
  -i, --include[=INCLUDE]      Filter files by [ext,type]. E.g., --filter=ext:jpg,ext:png,ext:gif,ext:heic or --filter=type:image
                               Available types: [archive|audio|video|image|font|office|pdf|postscript|richtext|php|message|text|application|xml|source|config]
  -e, --exclude[=EXCLUDE]      Exclude files by [ext,type]. E.g., --exclude=jpg,png,gif or --exclude=type:image
                               Available types: [archive|audio|video|image|font|office|pdf|postscript|richtext|php|message|text|application|xml|source|config]
      --by-letter[=BY-LETTER]  When by:letter is used: Length of the first letters to group by [default: 1]
  -h, --help                   Display help for the given command. When no command is given display help for the list command
      --silent                 Do not output any message
  -q, --quiet                  Only errors are displayed. All other output is suppressed
  -V, --version                Display this application version
      --ansi|--no-ansi         Force (or disable --no-ansi) ANSI output
  -n, --no-interaction         Do not ask any interactive question
  -v|vv|vvv, --verbose         Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

## TBD

* Add a dry run
* Omit log at the end if not wanted
* Sort into date:day folders
* Sort into folders by filename (with user defined number of leading letters)
* Write tests