<ul>
    <?php
    $User = new User;
    $rank = $User->getRank();

    $data = file_get_contents('app/package.json');
    $json = json_decode($data, true);

    foreach($json['menu'] as $key => $menu) {
        if($key == 'top' || $key == 'main' || $key == 'tools' || $key == 'options' || $key == 'site') {
            
            echo ucfirst($key) . '<br>';
            foreach($menu as $item) {
                if($item['permission'] <= $rank) {
                    echo '<li><a href="' . $item['location'] . '">' . $item['name'] . '</a></li>';
                
                    if(isset($item['sub'])) {
                        echo '<ul>';
                        
                        foreach($item['sub'] as $sub) {
                            if($sub['permission'] <= $rank) {
                                echo '<li><a href="' . $sub['location'] . '">' . $sub['name'] . '</a></li>';
                            }
                        }

                        echo '</ul>';
                    }
                }
            }

            $dirs = scandir('../packages');
            foreach($dirs as $dir) {
                if($dir != '.' && $dir != '..' && is_dir('../packages/' . $dir)) {
                    $packData = file_get_contents('../packages/' . $dir . '/package.json');
                    $packJson = json_decode($packData, true);

                    if(isset($packJson['menu'])) {
                        foreach(array_keys($packJson['menu']) as $packKey) {
                            if($key == $packKey) {
                                foreach($packJson['menu'][$packKey] as $packItem) {
                                    if($packItem['permission'] <= $rank) {
                                        echo '<li><a href="../packages/' . $dir . '/' . $packItem['location'] . '">[' . $packJson['name'] . '] ' . $packItem['name'] . '</a></li>';
                                    
                                        if(isset($packItem['sub'])) {
                                            echo '<ul>';
                                            
                                            foreach($packItem['sub'] as $packSub) {
                                                if($packSub['permission'] <= $rank) {
                                                    echo '<li><a href="../packages/' . $dir . '/' . $packSub['location'] . '">[' . $packJson['name'] . '] ' . $packSub['name'] . '</a></li>';
                                                }
                                            }
                                            
                                            echo '</ul>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            echo '<br>';
        }
    }
    ?>
</ul>