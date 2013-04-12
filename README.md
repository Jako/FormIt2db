FormIt2db/db2FormIt
================================================================================

FormIt hooks for saving/retreiving xPDO objects.

Features:
--------------------------------------------------------------------------------
FormIt2db/db2FormIt are two small snippets to save xPDO objects during FormIt posts and retreive them before displaying FormIt forms.

Installation:
--------------------------------------------------------------------------------
MODX Package Management

The xPDO models could be generated by i.e. MIGX or CMPGenerator.

Usage
--------------------------------------------------------------------------------

Snippet Properties for Formit2db

Property         | Description                                                                                      | Default
---------------- | ------------------------------------------------------------------------------------------------ | --------------
prefix           | Table prefix of the xPDO package.                                                                | MODX DB prefix
packagename      | Package name of the xPDO object.                                                                 | -
classname        | Class name of the xPDO object.                                                                   | -
where            | JSON encoded xPDO where clause - to update a row instead of creating a new one.                  | -
paramname        | Requested POST param - to update a row instead of creating a new one.                            | -
fieldname        | xPDO fieldname the POST param is compared with - to update a row instead of creating a  new one. | 'paramname'
arrayFormat      | Format to transform form fields that contains array data (i.e. checkboxes) into                  | csv
arrayFields      | JSON encoded array of form fields that contains array data                                       | []
removeFields     | JSON encoded array of form fields not saved in the xPDO object                                   | []

Snippet Properties for db2Formit

Property         | Description                                                                                      | Default
---------------- | ------------------------------------------------------------------------------------------------ | --------------
prefix           | Table prefix of the xPDO package.                                                                | MODX DB prefix
packagename      | Package name of the xPDO object.                                                                 | -
classname        | Class name of the xPDO object.                                                                   | -
where            | JSON encoded xPDO where clause - to retreive an existing row.                                    | -
paramname        | Requested REQUEST param - to retreive an existing row.                                           | -
fieldname        | xPDO fieldname the REQUEST param is compared with - to retreive an existing row.                 | 'paramname'
arrayFormat      | Format to transform database fields that contains array data (i.e. checkboxes) into              | csv
arrayFields      | JSON encoded array of database fields that are transformed into arrays                           | []
ignoreFields     | JSON encoded array of database fields that are not retreived into FormIt                         | []
notFoundRedirect | ID of the MODX resource the user is redirected to, if the requested row is not found             | 0

Note
--------------------------------------------------------------------------------

The snippets bases on the code in the following thread in MODX forum
http://forums.modx.com/thread/?thread=32560 