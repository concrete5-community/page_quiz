<?php 
defined('C5_EXECUTE') or die("Access Denied.");

$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();
?>

<style>
    .ccm-block-page-quiz-edit input,
    .ccm-block-page-quiz-edit textarea {
        display: block;
        width: 100%;
    }

    .ccm-block-page-quiz-edit .btn-success {
        margin-bottom: 20px;
    }

    .ccm-block-page-quiz-edit i.fa-sort-asc {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }

    .ccm-block-page-quiz-edit i:hover {
        color: #5cb85c;
    }

    .ccm-block-page-quiz-edit i.fa-sort-desc {
        position: absolute;
        top: 15px;
        cursor: pointer;
        right: 10px;
    }

    .ccm-page-quiz-entry {
        position: relative;
    }
</style>

<div class="ccm-block-page-quiz-edit">
    <div class="form-group">
        <?php 
        echo $form->label('title', t('Title / Question'));
        echo $form->text('title', $title);
        ?>
    </div>

    <span class="btn btn-success ccm-add-page-quiz-entry"><?php  echo t('Add Entry') ?></span>
    <?php 
    if ($entries) {
        foreach ($entries as $entry) {
            ?>
            <div class="ccm-page-quiz-entry well">
                <i class="fa-sort-asc fa"></i>
                <i class="fa-sort-desc fa"></i>

                <div class="form-group">
                    <?php 
                    echo $form->label('answer', t("Answer"));
                    echo $form->text('answer[]', $entry['answer']);
                    ?>
                </div>

                <div class="form-group">
                    <?php 
                    echo $form->label('isCorrect', t("Is Correct"));
                    echo $form->select('isCorrect[]', array(1 => t("Yes"), 0 => t("No")), $entry['isCorrect'], array('class' => 'form-control'));
                    ?>
                </div>

                <div class="form-group">
                    <label><?php  echo t('Feedback') ?></label>
                    <textarea class="redactor-content" name="feedback[]"><?php  echo $entry['feedback'] ?></textarea>
                </div>

                <?php 
                echo $form->hidden('sortOrder[]', $entry['sortOrder'], array('class' => 'ccm-page-quiz-entry-sort'));
                ?>

                <div class="form-group">
                    <span class="btn btn-danger ccm-delete-page-quiz-entry"><?php  echo t('Delete Entry'); ?></span>
                </div>
            </div>
            <?php 
        }
    }
    ?>

    <div class="ccm-page-quiz-entry well ccm-page-quiz-entry-template" style="display: none;">
        <i class="fa-sort-asc fa"></i>
        <i class="fa-sort-desc fa"></i>

        <div class="form-group">
            <label><?php  echo t('Answer') ?></label>
            <input type="text" name="answer[]" value=""/>
        </div>
        <div class="form-group">
            <label><?php  echo t('Is Correct?') ?></label>
            <select name="isCorrect[]" class="form-control">
                <option value="0"><?php  echo t("No"); ?></option>
                <option value="1"><?php  echo t("Yes"); ?></option>
            </select>
        </div>
        <div class="form-group">
            <label><?php  echo t('Feedback') ?></label>
            <textarea class='redactor-content' name="feedback[]"></textarea>
        </div>
        <input class="ccm-page-quiz-entry-sort" type="hidden" name="sortOrder[]" value=""/>

        <div class="form-group">
            <span class="btn btn-danger ccm-delete-page-quiz-entry"><?php  echo t('Delete Entry'); ?></span>
        </div>
    </div>
</div>


<script>
    (function() {
        var container = $('.ccm-block-page-quiz-edit');
        var doSortCount = function () {
            $('.ccm-add-page-quiz-entry', container).each(function (index) {
                $(this).find('.ccm-page-quiz-entry-sort').val(index);
            });
        };

        doSortCount();
        var cloneTemplate = $('.ccm-page-quiz-entry-template', container).clone(true);
        cloneTemplate.removeClass('.ccm-page-quiz-entry-template');
        $('.ccm-page-quiz-entry-template').remove();

        $(cloneTemplate).add($('.ccm-page-quiz-entry', container)).find('.ccm-delete-page-quiz-entry').click(function () {
            var deleteIt = confirm('<?php  echo t('Are you sure?') ?>');
            if (deleteIt == true) {
                $(this).closest('.ccm-page-quiz-entry').remove();
                doSortCount();
            }
        });

        container.find('.redactor-content').redactor({
            minHeight: 200,
            'concrete5': {
                filemanager: <?php echo $fp->canAccessFileManager()?>,
                sitemap: <?php echo $tp->canAccessSitemap()?>,
                lightbox: true
            }
        });

        var attachSortDesc = function ($obj) {
            $obj.click(function () {
                var myContainer = $(this).closest('.ccm-page-quiz-entry');
                myContainer.insertAfter(myContainer.next('.ccm-page-quiz-entry'));
                doSortCount();
            });
        };

        var attachSortAsc = function ($obj) {
            $obj.click(function () {
                var myContainer = $(this).closest('.ccm-page-quiz-entry');
                myContainer.insertBefore(myContainer.prev('.ccm-page-quiz-entry'));
                doSortCount();
            });
        };

        $('i.fa-sort-desc', container).each(function () {
            attachSortDesc($(this));
        });

        $('i.fa-sort-asc', container).each(function () {
            attachSortAsc($(this));
        });

        $('.ccm-add-page-quiz-entry', container).click(function () {
            var newClone = cloneTemplate.clone(true);

            newClone.show().find('.redactor-content').redactor({
                minHeight: 200,
                'concrete5': {
                    filemanager: <?php echo $fp->canAccessFileManager()?>,
                    sitemap: <?php echo $tp->canAccessSitemap()?>,
                    lightbox: true
                }
            });
            container.append(newClone);
            attachSortAsc(newClone.find('i.fa-sort-asc'));
            attachSortDesc(newClone.find('i.fa-sort-desc'));
            var thisModal = $(this).closest('.ui-dialog-content');
            var newSlide = $('.ccm-page-quiz-entry').last();
            thisModal.scrollTop(newSlide.offset().top);
            doSortCount();
        });
    }());
</script>
