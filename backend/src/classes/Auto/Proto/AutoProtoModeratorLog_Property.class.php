<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.1.master at 2017-03-29 18:26:37                    *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/

	abstract class AutoProtoModeratorLog_Property extends ProtoModeratorLog
	{
		protected function makePropertyList()
		{
			return
				array_merge(
					parent::makePropertyList(),
					array(
						'propertyName' => LightMetaProperty::fill(new LightMetaProperty(), 'propertyName', 'property_name', 'string', null, 255, false, true, false, null, null),
						'oldValue' => LightMetaProperty::fill(new LightMetaProperty(), 'oldValue', 'old_value', 'string', null, 255, false, true, false, null, null),
						'newValue' => LightMetaProperty::fill(new LightMetaProperty(), 'newValue', 'new_value', 'string', null, 255, false, true, false, null, null),
						'id' => LightMetaProperty::fill(new LightMetaProperty(), 'id', null, 'integerIdentifier', 'ModeratorLog_Property', 8, true, true, false, null, null)
					)
				);
		}
	}
?>