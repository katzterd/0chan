<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.1.master at 2017-03-29 18:26:37                    *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/

	abstract class AutoProtoModeratorLog_Ban extends ProtoModeratorLog
	{
		protected function makePropertyList()
		{
			return
				array_merge(
					parent::makePropertyList(),
					array(
						'ban' => LightMetaProperty::fill(new LightMetaProperty(), 'ban', 'ban_id', 'identifier', 'Ban', null, false, false, false, 1, 3),
						'id' => LightMetaProperty::fill(new LightMetaProperty(), 'id', null, 'integerIdentifier', 'ModeratorLog_Ban', 8, true, true, false, null, null)
					)
				);
		}
	}
?>