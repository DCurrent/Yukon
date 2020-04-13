# What Is Yukon?

Yukon is an object-oriented library that serves as an abstraction and control layer for SQLSRV driver. 

[SQLSRV](http://php.net/manual/en/book.sqlsrv.php) is a PHP database access driver developed by Microsoft which replaces the now defunct MSSQL driver and serves as an alternate to the more commonly used PDO. Because it is more specialized than the intentionally platform agnostic PDO, sqlsrv requires fewer modifications to your PHP installation and offers [several advantages when working with MSSQL databases](https://blogs.msdn.microsoft.com/brian_swan/2010/04/20/comparing-the-sqlsrv-and-pdo-apis/). Unfortunately, it (sqlsrv) is wrapped in a rather clumsy procedural interface.

Yukon seeks to solve this latter caveat by creating an object-oriented interface for your applications to interact with the database engine. Additionally, Yukon is fully extensible and future proofed in every way possible. If you ever need to switch drivers or database engines, you need only modify Yukon as opposed to your application.

## Name Origin

Yukon is the primary river of Alaska, bisecting the state. It is at once a  massive and dangerous barrier, but also a life giving resource and transport when leveraged proorly - just like databases to an application.

# Installation / Use

See the [Yukon Wiki](https://github.com/DCurrent/Yukon/wiki) for full documentation and instructions.
