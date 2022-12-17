<?php defined('BASEPATH') OR exit('No direct script access allowed');

# Default routes
$route['default_controller'] = 'PersonalController';
// $route['404_override'] = 'Errors';
$route['translate_uri_dashes'] = FALSE;

####################################################################################################################################
################################################### SECURITY RELATED ###############################################################
####################################################################################################################################
$route['feature/authentication']                              = 'HomeController/twoFactorAuthentication';
$route['feature/access']                                      = 'HomeController/featureAccessCheck';

####################################################################################################################################
###################################################### APP RELATED #################################################################
####################################################################################################################################
$route['terms-and-conditions']                                = 'InfoController/termsAndConditions';
$route['privacy-policy']                                      = 'InfoController/privacyPolicy';
$route['about-us']                                            = 'InfoController/aboutUs';
$route['contact-us']                                          = 'InfoController/contactUs';
$route['contact/form/insert']                                 = 'InfoController/insertContactForm';

####################################################################################################################################
######################################################### AUTH #####################################################################
####################################################################################################################################
$route['auth/register']                                       = 'AuthController/register';
$route['auth/user/insert']                                    = 'AuthController/insertUser';
$route['auth/login']                                          = 'AuthController/login';
$route['auth/user/login']                                     = 'AuthController/userLogin';
$route['auth/forgotpassword']                                 = 'AuthController/forgotPassword';
$route['auth/forgotpassword/sendmail']                        = 'AuthController/forgotPasswordSendMail';
$route['auth/resetpassword/view']                             = 'AuthController/resetPasswordView';
$route['auth/resetpassword']                                  = 'AuthController/resetPassword';
$route['auth/change/password']                                = 'AuthController/changePassword';
$route['auth/delete']                                         = 'AuthController/userDelete';
$route['auth/logout']                                         = 'AuthController/logout';
$route['auth/twofactorauthentication']                        = 'AuthController/twoFactorAuthentication';
$route['auth/update/fcm_token']                               = 'AuthController/updateFcmToken';
$route['auth/account/verify/sendmail']                        = 'AuthController/verifyAccountSendMail';
$route['auth/account/verify']                                 = 'AuthController/verifyAccount';

####################################################################################################################################
########################################################## PROFILE #################################################################
####################################################################################################################################
$route['profile']                                             = 'UserController/profile';
$route['profile/image/upload']                                = 'UserController/uploadImage';
$route['profile/details/upload']                              = 'UserController/uploadUserDetails';

####################################################################################################################################
################################################## PERSONAL RESPONSIBILITIES #######################################################
####################################################################################################################################
$route['responsibilities/personal']                           = 'UserController/personalResponsibilities';
$route['responsibilities/personal/add']                       = 'UserController/personalResponsibilitiesAdd';
$route['responsibilities/personal/list']                      = 'UserController/personalResponsibilitiesList';
$route['responsibilities/personal/insert']                    = 'UserController/insertPersonalResponsibilities';
$route['responsibilities/personal/update']                    = 'UserController/updatePersonalResponsibilities';
$route['responsibilities/personal/delete']                    = 'UserController/deletePersonalResponsibilities';

####################################################################################################################################
########################################################## SETTINGS ################################################################
####################################################################################################################################
$route['settings']                                            = 'UserController/settings';
$route['settings/feature']                                    = 'UserController/updateFeatureSettings';
$route['settings/updatepin']                                  = 'UserController/updatePin';
$route['settings/security']                                   = 'UserController/updateSecuritySettings';

####################################################################################################################################
######################################################## FRIENDS ###################################################################
####################################################################################################################################
$route['friends']                                             = 'FriendsController/index';
$route['friends/friends/view']                                = 'FriendsController/friendsView';
$route['friends/friends/list']                                = 'FriendsController/friendsList';
$route['friends/followers/view']                              = 'FriendsController/followersView';
$route['friends/followers/list']                              = 'FriendsController/followersList';
$route['friends/requests']                                    = 'FriendsController/requests';
$route['friends/search/view']                                 = 'FriendsController/searchView';
$route['friends/search/list']                                 = 'FriendsController/searchList';
$route['friends/updatesearchfriend']                          = 'FriendsController/updateSearchFriend';
$route['friends/acceptrejectfriend']                          = 'FriendsController/acceptRejectFriend';

####################################################################################################################################
####################################################### PERSONAL ###################################################################
####################################################################################################################################
$route['personal']                                            = 'PersonalController/index';
$route['personal/overview']                                   = 'PersonalController/overviewPersonal';
$route['personal/add']                                        = 'PersonalController/addPersonal';
$route['personal/insert']                                     = 'PersonalController/insertPersonal';
$route['personal/update']                                     = 'PersonalController/updatePersonal';
$route['personal/delete']                                     = 'PersonalController/deletePersonal';
$route['personal/daytoday']                                   = 'PersonalController/dayToDay';
$route['personal/daytoday/overview']                          = 'PersonalController/dayToDayOverview';
$route['personal/monthly']                                    = 'PersonalController/monthly';
$route['personal/monthly/overview']                           = 'PersonalController/monthlyOverview';
$route['personal/monthly/charts']                             = 'PersonalController/monthlyCharts';
$route['personal/yearly']                                     = 'PersonalController/yearly';
$route['personal/yearly/charts']                              = 'PersonalController/yearlyCharts';
$route['personal/responsibilities']                           = 'PersonalController/responsibilities';
$route['personal/custom']                                     = 'PersonalController/custom';
$route['personal/custom/overview']                            = 'PersonalController/customOverview';
$route['personal/expenses']                                   = 'PersonalController/getExpenses';
$route['personal/modalview']                                  = 'PersonalController/expensesModalview';

####################################################################################################################################
######################################################## EVENTS ####################################################################
####################################################################################################################################
$route['events']                                              = 'EventsController/index';
$route['events/view']                                         = 'EventsController/viewEvents';
$route['event/add']                                           = 'EventsController/addEvent';
$route['event/insert']                                        = 'EventsController/insertEvent';
$route['event/edit/(:any)']                                   = 'EventsController/editEvent/$1';
$route['event/update']                                        = 'EventsController/updateEvent';
$route['event/delete/(:any)']                                 = 'EventsController/deleteEvent/$1';
$route['event/member/update/status']                          = 'EventsController/memberUpdateStatus';
$route['event/view/(:any)']                                   = 'EventsController/viewEvent/$1';
$route['event/expenses/individual/insert']                    = 'EventsController/insertIndividualEventExpenses';
$route['event/expenses/individual/overview/(:any)']           = 'EventsController/individualEventExpensesOverview/$1';
$route['event/expenses/individual/details/(:any)']            = 'EventsController/individualEventExpensesDetails/$1';
$route['event/expenses/individual/update']                    = 'EventsController/updateIndividualEventExpenses';
$route['event/expenses/individual/delete']                    = 'EventsController/deleteIndividualEventExpenses';
$route['event/expenses/individual/status/(:any)']             = 'EventsController/individualEventExpensesStatus/$1';
$route['event/expenses/individual/close']                     = 'EventsController/closeIndividualEventExpenses';
$route['event/expenses/individual/addtopersonal']             = 'EventsController/addIndividualEventExpensesToPersonal';
$route['event/group/exit/(:any)']                             = 'EventsController/exitGroup/$1';
$route['event/expenses/group/add/(:any)']                     = 'EventsController/addGroupEventExpenses/$1';
$route['event/expenses/group/insert']                         = 'EventsController/insertGroupEventExpenses';
$route['event/expenses/group/personal/(:any)']                = 'EventsController/groupEventExpensesPersonal/$1';
$route['event/expenses/group/update']                         = 'EventsController/updateGroupEventExpenses';
$route['event/expenses/group/delete']                         = 'EventsController/deleteGroupEventExpenses';
$route['event/expenses/group/view/(:any)']                    = 'EventsController/groupEventExpensesView/$1';
$route['event/expenses/group/overview/(:any)']                = 'EventsController/groupEventExpensesOverview/$1';
$route['event/expenses/group/charts/(:any)']                  = 'EventsController/groupEventExpensesCharts/$1';
$route['event/expenses/group/graphs/(:any)']                  = 'EventsController/groupEventExpensesGraphs/$1';
$route['event/expenses/group/splitshare/(:any)']              = 'EventsController/groupEventExpensesSplitShare/$1';
$route['event/expenses/group/close']                          = 'EventsController/closeGroupEventExpenses';
$route['event/expenses/group/payments/list/(:any)']           = 'EventsController/groupEventPayments/$1';
$route['event/expenses/group/payments/paid']                  = 'EventsController/paidGroupEventExpenses';
$route['event/expenses/group/payments/debit']                 = 'EventsController/debitGroupEventExpenses';
$route['event/expenses/group/addtopersonal']                  = 'EventsController/addGroupEventExpensesToPersonal';

####################################################################################################################################
#################################################### NOTIFICATIONS #################################################################
####################################################################################################################################
$route['notifications']                                       = 'NotificationsController';
$route['notifications/unread/count']                          = 'NotificationsController/fetchUnReadNotificationsCount';
$route['notifications/update']                                = 'NotificationsController/updateNotifications';

####################################################################################################################################
###################################################### ACCOUNTS ####################################################################
####################################################################################################################################
$route['accounts']                                            = 'AccountsController/index';
$route['accounts/add']                                        = 'AccountsController/insertAccount';
$route['accounts/search']                                     = 'AccountsController/searchAccounts';
$route['account/update/(:any)']                               = 'AccountsController/updateAccount/$1';
$route['account/delete/(:any)']                               = 'AccountsController/deleteAccount/$1';
$route['account/view/(:any)']                                 = 'AccountsController/viewAccount/$1';
$route['account/view/(:any)/transactions']                    = 'AccountsController/accountTransactions/$1';
$route['account/view/(:any)/transactions/overview']           = 'AccountsController/accountTransactionsOverview/$1';
$route['account/view/(:any)/transactions/add']                = 'AccountsController/addAccountTransaction/$1';
$route['account/view/(:any)/transaction/update/(:any)']       = 'AccountsController/updateAccountTransaction/$1/$2';
$route['account/view/(:any)/transaction/delete/(:any)']       = 'AccountsController/deleteAccountTransaction/$1/$2';

####################################################################################################################################
################################################## STRESS TESTING ##################################################################
####################################################################################################################################
$route['stress/testing/personalexpenses']                     = 'StressTesting/TestingController/addPersonalExpenses';