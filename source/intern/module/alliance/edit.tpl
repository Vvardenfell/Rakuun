<? if ($this->hasPanel('delete')): ?>
	<? $this->displayPanel('edit'); ?>
<? endif; ?>
<? if ($this->hasPanel('delete')): ?>
	<? $this->displayPanel('delete'); ?>
<? endif; ?>
<? if ($this->hasPanel('kick')): ?>
	<? $this->displayPanel('kick'); ?>
<? endif; ?>
<? if ($this->hasPanel('activity')): ?>
	<? $this->displayPanel('activity'); ?>
<? endif; ?>
<? if ($this->hasPanel('mail')): ?>
	<? $this->displayPanel('mail'); ?>
<? endif; ?>
<? if ($this->hasPanel('invite')): ?>
	<? $this->displayPanel('invite'); ?>
<? endif; ?>
<? if ($this->hasPanel('ranks')): ?>
	<? $this->displayPanel('ranks'); ?>
	<? $this->displayPanel('new_rank'); ?>
<? endif; ?>