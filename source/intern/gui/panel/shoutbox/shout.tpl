<? if ($this->hasErrors()): ?>
	<? $this->displayErrors(); ?>
<? endif; ?>
<div class="shout_header">
<? $this->displayPanel('userlink'); ?>, am <? $this->displayPanel('date'); ?>
, <? $this->displayPanel('answerlink'); ?>
</div>
<div class="shout_text">
	<?= nl2br(Text::escapeHTML($this->params->shout->text)); ?>
</div>