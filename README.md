Meredith
============
2016-01-04



Php plugin for implementing a crud strategy based on the jquery datatables plugin.




Resources
------------

To learn how to use Meredith, apart from reading the source code, there are the following resources:


- [meredith adaman tutorial](https://github.com/lingtalfi/meredith-adaman), this is actually the non-official documentation




Dependencies
------------------

- [lingtalfi/Bat 1.23](https://github.com/lingtalfi/Bat)
- [lingtalfi/QuickPdo 1.4.0](https://github.com/lingtalfi/QuickPdo)
- [lingtalfi/Tim 1.1.0](https://github.com/lingtalfi/Tim)




History Log
------------------

        
- 2.5.0 -- 2016-01-15

    - FormDataProcessor: fix default value is null instead of 0
    - insert_update_row.php: add possibility to cancel an insert from the meredith flow
        
        
- 2.4.0 -- 2016-01-15

    - insert_update_row.php: can now define the table from the meredith workflow
    - insert_update_row.php: add handling of default error message
    - insert_update_row.php: logging pdo exceptions
    
    
- 2.3.0 -- 2016-01-15

    - add ColisControl
    
        
- 2.2.0 -- 2016-01-13

    - add ListHandlerInterface.setWhere 
    - fix bug in (wass0) /libs/meredith/service/datatables_server_side_processor.php 
    - add Data2ButtonContentTransformer
    - add SingleSelectControlInterface  
    - add handling for foreign key in insert/update service  
    - fix bug in /libs/meredith/service/insert_update_row.php 

    
- 2.0.0 -- 2016-01-05

    - introducing ListPreConfigScript  
    - moved header buttons from ListHandler to ListPreConfigScript  
    
    
- 1.0.4 -- 2016-01-04

    - fix BootstrapControlsRenderer.renderMonoStatusControl default value for switchery  
    
    
- 1.0.3 -- 2016-01-04

    - clean MainController code
    
    
- 1.0.0 -- 2016-01-04

    - initial commit
    
    





