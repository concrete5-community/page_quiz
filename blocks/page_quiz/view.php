<?php 
defined('C5_EXECUTE') or die("Access Denied.");
?>
<div class="ccm-page-quiz">
    <div class="title"><?php  echo h($title); ?></div>
    <?php 
    if ($entries) {
        ?>
        <div class="answers-text">
            <?php 
            $i = 0;
            foreach ($entries as $entry) {
                echo '<div class="answer answer-text" data-is-correct="' . (int) $entry['isCorrect'].'">'. chr(65 + $i) . ') ' . h($entry['answer']) .'</div>';
                $i++;
            }
            ?>
        </div>

        <div class="answers-buttons">
            <?php 
            $i = 0;
            foreach ($entries as $entry) {
                ?>
                <a href="#" class="answer answer-btn" data-is-correct="<?php  echo (int) $entry['isCorrect']; ?>"><?php  echo chr(65 + $i) ?></a>
                <?php 
                $i++;
            }
            ?>
        </div>

        <div class="answers-feedback">
            <?php 
            foreach ($entries as $entry) {
                ?>
                <div class="answer">
                    <?php 
                    echo $controller->translateFrom($entry['feedback']);
                    ?>
                </div>
                <?php 
            }
            ?>
        </div>
        <?php 
    }
    ?>
</div>
