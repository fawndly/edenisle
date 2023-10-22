# Integration tests
Sometimes it's really cumbersome to test the whole website by hand. These integration tests are designed to run through common scenarios and make sure that main functionality is still preserved. This is going to allow us to write code with a little more confidence.

## Requirements to run
These tests are done in Ruby with [Watir](https://watir.github.io/) because PHP doesn't have a good integration test framework in comparison. They should be syntactically simple enough so that if you're not familiar with Ruby you can change some text and copy/paste stuff around and get a test going. 

### Ruby
https://www.ruby-lang.org/en/documentation/installation/

### Chromedriver
https://sites.google.com/a/chromium.org/chromedriver/getting-started

## Setting up
Once you have Ruby and Chromedriver installed and setup. You need to `cd` into the `tests/` directory and run `bundle install`.

Your output should look something similar to:
```bash
$ bundle install
Fetching gem metadata from https://rubygems.org/............
Fetching version metadata from https://rubygems.org/...
Fetching dependency metadata from https://rubygems.org/..
Resolving dependencies...
Using ffi 1.9.14
Installing diff-lcs 1.2.5
Installing rspec-support 3.5.0
Using rubyzip 1.2.0
Using websocket 1.2.3
Using bundler 1.11.2
Using childprocess 0.5.9
Installing rspec-core 3.5.4
Installing rspec-expectations 3.5.0
Installing rspec-mocks 3.5.0
Using selenium-webdriver 3.0.1
Installing rspec 3.5.0
Using watir 6.0.1
Installing watir-rspec 3.0.0
Bundle complete! 3 Gemfile dependencies, 14 gems now installed.
Use `bundle show [gemname]` to see where a bundled gem is installed.
```

:+1: That would be good news.

##