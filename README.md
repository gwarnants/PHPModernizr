PHPModernizr
============

A server-side modernizr library that gives the ability to use last built-in PHP functions on old PHP versions.
Can be helpful to work on old PHP 4 servers or restricted shared hosts (where some functions could be voluntarily disabled).

```php
include 'PHPModernizr.php';
```

Just include the library and get ready to use all theses brand-new functions regardless of your PHP configuration :

- apache_response_headers()
- array_column()
- array_combine()
- array_diff_uassoc()
- array_fill_keys()
- array_replace()
- array_replace_recursive()
- array_udiff()
- array_udiff_assoc()
- array_udiff_uassoc()
- array_walk_recursive()
- boolval()
- ctype_alnum()
- ctype_alpha()
- ctype_cntrl()
- ctype_digit()
- ctype_graph()
- ctype_lower()
- ctype_print()
- ctype_punct()
- ctype_space()
- ctype_upper()
- ctype_xdigit()
- file_get_contents()
- file_put_contents()
- gethostname()
- header_remove()
- headers_list()
- hex2bin()
- http_build_query()
- image_type_to_extension()
- imageflip()
- json_encode()
- lcfirst()
- mysqli_fetch_all()
- parse_ini_string()
- scandir()
- str_getcsv()
- str_shuffle()
- str_split()
- str_word_count()
- stream_resolve_include_path()
- stripos()
- strpbrk()
- substr_compare()
- sys_get_temp_dir()
