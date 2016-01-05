<script>
    window.meredithRegistry.buttons = [

        <?php  
        
            /**
            * @var ListButtonCodeInterface[] $buttons
            */
            use Meredith\ListButtonCode\ListButtonCodeInterface;
            
            
            $c = 0;
            foreach($buttons as $b){
                if(0 !== $c){
                    echo "," . PHP_EOL;
                }
                echo $b->render();
                $c++;
            }
        
        ?>
    ];
</script>