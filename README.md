# Nuf (~Fun)

**Nuf** is an easy way to manage simple home pages, it supports Markdown syntax
and provides basic template support. It uses bootstrap as default template
which is based on Twitter's bootstrap CSS. And written in PHP. No DB required.

To add or edit a page you just need traditional tools; an editor (Vim) and an
Ftp application (Mc is fine) thats all.

## Installation

### Getting Nuf

- If you know how to use Git its just simple as:

        $ git clone git://github.com/gokmen/Nuf.git

  and then you can copy or move the newly created Nuf directory to the your
  target directory.

- If you don't know how to use Git, just download it from
  <https://github.com/gokmen/Nuf/downloads>

### Requirements

- Apache or similar web server
- PHP 5
- Access rights to the files

To install it just put all files to the your web page **root** directory,
if you need to use your user dir (like `/home/user/public_html`) for hosting,
also take a look at the `.htaccess` file.

### Configuration

All configuration is under the `config.ini` file with comments.
It has very basic structure; you just need to be careful with `url` setting.

## Content editing

### Adding a new page

- Open `pages.ini` and add new page like:

        [pagename]
        name = Name in Menu

- Add a new file in to the `pages` folder with using the same page name:
  `pages/pagename.txt`

> You can also use `.html` as page suffix.

### Adding sub pages

- You can also add sub pages, to do this just use `pagename` with its root
  as new page name:

        [rootpage/subpage]
        name = Sub Page of Root Page

- And then you need to create a folder for `rootpage` under `pages`
  directory: `pages/rootpage`
- You also need to store the `subpage` content under this directory:
  `pages/rootpage/subpage.txt`

### Adding a remote Url to the menu

- If you need to add some remote links to the menu you can create a dummy page
  for it, just add a page to `pages.ini` something like that:

        [nuf]
        name = Nuf
        link = http://github.com/gokmen/Nuf

- It will create a menu entry named **Nuf** in your menubar which is
  linked to the Nuf's github page.

