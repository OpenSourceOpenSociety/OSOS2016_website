<?php

class KS_Giveaways_Vendor_Mailchimp_Error extends Exception {}
class KS_Giveaways_Vendor_Mailchimp_HttpError extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * The parameters passed to the API call are invalid or not provided when required
 */
class KS_Giveaways_Vendor_Mailchimp_ValidationError extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_ServerError_MethodUnknown extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_ServerError_InvalidParameters extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Unknown_Exception extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Request_TimedOut extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Zend_Uri_Exception extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_PDOException extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Avesta_Db_Exception extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_XML_RPC2_Exception extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_XML_RPC2_FaultException extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Too_Many_Connections extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Parse_Exception extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_Unknown extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_Disabled extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_DoesNotExist extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_NotApproved extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_ApiKey extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_UnderMaintenance extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_AppKey extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_IP extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_DoesExist extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_InvalidRole extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_InvalidAction extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_MissingEmail extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_CannotSendCampaign extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_MissingModuleOutbox extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_ModuleAlreadyPurchased extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_ModuleNotPurchased extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_User_NotEnoughCredit extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_MC_InvalidPayment extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_DoesNotExist extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_InvalidInterestFieldType extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_InvalidOption extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_InvalidUnsubMember extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_InvalidBounceMember extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_AlreadySubscribed extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_NotSubscribed extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_InvalidImport extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_MC_PastedList_Duplicate extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_MC_PastedList_InvalidImport extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Email_AlreadySubscribed extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Email_AlreadyUnsubscribed extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Email_NotExists extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Email_NotSubscribed extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_MergeFieldRequired extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_CannotRemoveEmailMerge extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_Merge_InvalidMergeID extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_TooManyMergeFields extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_InvalidMergeField extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_InvalidInterestGroup extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_List_TooManyInterestGroups extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_DoesNotExist extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_StatsNotAvailable extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_InvalidAbsplit extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_InvalidContent extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_InvalidOption extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_InvalidStatus extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_NotSaved extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_InvalidSegment extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_InvalidRss extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_InvalidAuto extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_MC_ContentImport_InvalidArchive extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_BounceMissing extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Campaign_InvalidTemplate extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_EcommOrder extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Absplit_UnknownError extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Absplit_UnknownSplitTest extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Absplit_UnknownTestType extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Absplit_UnknownWaitUnit extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Absplit_UnknownWinnerType extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Absplit_WinnerNotSelected extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_Analytics extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_DateTime extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_Email extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_SendType extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_Template extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_TrackingOptions extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_Options extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_Folder extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_URL extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Module_Unknown extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_MonthlyPlan_Unknown extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Order_TypeUnknown extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_PagingLimit extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Invalid_PagingStart extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Max_Size_Reached extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_MC_SearchException extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Goal_SaveFailed extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Conversation_DoesNotExist extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Conversation_ReplySaveFailed extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_File_Not_Found_Exception extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Folder_Not_Found_Exception extends KS_Giveaways_Vendor_Mailchimp_Error {}

/**
 * None
 */
class KS_Giveaways_Vendor_Mailchimp_Folder_Exists_Exception extends KS_Giveaways_Vendor_Mailchimp_Error {}


