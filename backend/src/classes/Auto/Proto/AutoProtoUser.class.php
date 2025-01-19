<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.1.master at 2021-08-13 22:29:03                    *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/

	abstract class AutoProtoUser extends AbstractProtoClass
	{
		protected function makePropertyList()
		{
			return array(
				'id' => LightMetaProperty::fill(new LightMetaProperty(), 'id', null, 'integerIdentifier', 'User', 8, true, true, false, null, null),
				'createDate' => LightMetaProperty::fill(new LightMetaProperty(), 'createDate', 'create_date', 'timestamp', 'Timestamp', null, true, true, false, null, null),
				'login' => LightMetaProperty::fill(new LightMetaProperty(), 'login', null, 'string', null, 255, true, true, false, null, null),
				'password' => LightMetaProperty::fill(new LightMetaProperty(), 'password', null, 'string', null, 64, true, true, false, null, null),
				'role' => LightMetaProperty::fill(new LightMetaProperty(), 'role', 'role_id', 'enum', 'UserRole', null, true, false, false, 1, 3),
				'deleted' => LightMetaProperty::fill(new LightMetaProperty(), 'deleted', null, 'boolean', null, null, true, true, false, null, null),
				'showNsfw' => LightMetaProperty::fill(new LightMetaProperty(), 'showNsfw', 'show_nsfw', 'boolean', null, null, true, true, false, null, null),
				'treeView' => LightMetaProperty::fill(new LightMetaProperty(), 'treeView', 'tree_view', 'boolean', null, null, true, true, false, null, null),
				'viewDeleted' => LightMetaProperty::fill(new LightMetaProperty(), 'viewDeleted', 'view_deleted', 'boolean', null, null, true, true, false, null, null),
				'customCss' => LightMetaProperty::fill(new LightMetaProperty(), 'customCss', 'custom_css', 'string', null, 100000, false, true, false, null, null),
				'favouriteBoards' => LightMetaProperty::fill(new LightMetaProperty(), 'favouriteBoards', 'favourite_boards_id', 'identifierList', 'FavouriteBoard', null, false, false, false, 2, null),
				'posts' => LightMetaProperty::fill(new LightMetaProperty(), 'posts', 'posts_id', 'identifierList', 'Post', null, false, false, false, 2, null),
				'bans' => LightMetaProperty::fill(new LightMetaProperty(), 'bans', 'bans_id', 'identifierList', 'Ban', null, false, false, false, 2, null),
				'identities' => LightMetaProperty::fill(new LightMetaProperty(), 'identities', 'identities_id', 'identifierList', 'UserIdentity', null, false, false, false, 2, null),
				'invites' => LightMetaProperty::fill(new LightMetaProperty(), 'invites', 'invites_id', 'identifierList', 'Invite', null, false, false, false, 2, null),
				'watchedThreads' => LightMetaProperty::fill(new LightMetaProperty(), 'watchedThreads', 'thread_id', 'identifierList', 'Thread', null, false, false, false, 3, null)
			);
		}
	}
?>