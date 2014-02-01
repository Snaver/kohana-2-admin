kohana-2-admin
==============
Barebones admin system built on Kohana 2.3.1 with Bootstrap CSS framework.

![Example 1](Screenshot-1.png?raw=true)
![Example 2](Screenshot-2.png?raw=true)
![Example 2 Multi File Uploader](Screenshot-3.png?raw=true)

Projects utilised
=================
[Kohana - v2.3.1](https://github.com/Snaver/kohana-2.3.1)  
[Humanise-PHP](https://github.com/iantearle/Humanise-PHP)

[jQuery - v1.10.2](https://github.com/jquery/jquery)  
[jQuery UI - v1.10.3](https://github.com/jquery/jquery-ui)  
[Bootstrap - v3.0.3 customised](https://github.com/twbs/bootstrap)  
[Bootbox - v4.0.0](https://github.com/makeusabrew/bootbox)  
[Bootstrap Datetimepicker - v2.1.11](https://github.com/Eonasdan/bootstrap-datetimepicker)  
[Moment - v2.2.1](https://github.com/moment/moment)  
[jQuery-File-Upload - v5.32.3](https://github.com/blueimp/jQuery-File-Upload)  

[html5shiv - v3.6.2](https://github.com/aFarkas/html5shiv)  
[selectivizr - v1.0.2](https://github.com/keithclark/selectivizr)  
[respond - v1.3.0](https://github.com/scottjehl/Respond)  
[tmpl - v2.4.1](https://github.com/blueimp/JavaScript-Templates)

Logins
======
Two test logins are provided 'test' and 'test2' with both passwords set to '123456'.

Router / Controllers
====================
The default Routing of URLs -> Controllers has been slightly modified to make things a bit more cleaner.

* All /admin/* URLs will first try and load a Controller file named 'be_%SECTION_NAME%', if a Controller of that name cannot be found then it will fall back to normal routing.
* All other URLs will first try and load a Controller file named 'fe_%SECTION_NAME%', if a Controller of that name cannot be found then it will fall back to normal routing.

This was done for a few reasons. Normally you would have an admin directory in the Controllers folder with the same name, however because of class naming convention clash this was not possible.

There is a single custom Admin route in place that basically removes the admin part from the requested URL when being parsed by the Router code.

Admin section principles
========================
All admin section Controllers/Models should extend the base Admin Controller/Model, this stops there being repeated code in every single Controller/Model. As a minimum for an admin section you must have the following files:

* **Controller** - Set some class properties and have a basic __construct() method.  
* **i18n** - Language file.  
* **Model** - Set some class constants and properties and also define the database fields.  

You can easily extend/amend functionality by overriding the default methods contained in the admin base Controller or Model, you can acheive this either by having the same named method or by overriding, running the parent method and then performing your own logic. I.e. parent::method($args);

Example modules
---------------
**Example 1** shows how minimal a section can be, no overriding of methods and simply relying on the functionality inherited from the base Controllers.

**Example 2** shows how you can change/extend certain functionality to suit your needs. Two single upload fields, Multiple uploads via the Files tab, custom validation rules (Model), custom field layout (tab-0.php) and custom template variables set (Controller function section_details()).

**Example 3** shows how you can modify the list pages by extending both Controller and Model methods. In this example a type field is used to add tabs to the list page so that you can quickly sort and filter the data.

Fields
------
There are 9 supported field types: Checkbox, File, Input, Input Date, Input Number, Input Password, Radio, Select and Textarea. For each field in the Model a type is set, this is then used to load corresponding view which contains the markup and logic for that type.

Database table schema
=====================
For consistency all database tables and columns should use the following convention..  

* Table name should be plural of the object that it is representing.. I.e. for page the table name would be pages, etc.
* Column names should be prefixed the singular name of that object.. I.e. for pages table the column would be page_.
* Tables should always have the following fields: \_status(int), \_created_date(datetime), \_updated_date(datetime), \_deleted(int), \_deleted_date(datetime) and \_last_editor(int).

Improvements
============
Obviously normally you wouldn't use Kohana 2 as it's a bit dated now, however it's the framework I personally have most experience with. Saying that, it does the job - better than Procedural non [OOP code](http://stackoverflow.com/questions/1530868/simple-explanation-php-oop-vs-procedural). If I were to use a modern framework I'd use either [laravel](https://github.com/laravel/laravel) or [kohana 3](https://github.com/kohana/kohana).

* **JavaScript** - Use a JavaScript‎ framework to better architect the logic, making it easily readable and extendable
 
WIP / Not finished
============

* **Searching** - Need to figure out how the top search bar would work searching all items
* **Multi-File-Upload** - You can't remove items from Multi File Uploader after they've been added
* **Date time fields** - Need to add datetime and time only field type