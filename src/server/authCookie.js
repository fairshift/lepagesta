
// import moment from 'moment'

 
export const setAuthData = (req, store) => {

	var auth_token = null;
	if(typeof req.cookies['connect.sid'] !== 'undefined'){
		auth_token = req.cookies['connect.sid']
	}

	store.dispatch({ 
	  type: 'boilerplate/App/AUTH_SET_FROM_COOKIE', 
	  token: auth_token,
	  online: true
	})

   return true;
}

// Javascript date formats - internationalization (intl)
export const formats = {
   "ar-SA" : "dd/MM/yy",
   "bg-BG" : "dd.M.yyyy",
   "ca-ES" : "dd/MM/yyyy",
   "zh-TW" : "yyyy/M/d",
   "cs-CZ" : "d.M.yyyy",
   "da-DK" : "dd-MM-yyyy",
   "de-DE" : "dd.MM.yyyy",
   "el-GR" : "d/M/yyyy",
   "en-US" : "M/d/yyyy",
   "fi-FI" : "d.M.yyyy",
   "fr-FR" : "dd/MM/yyyy",
   "he-IL" : "dd/MM/yyyy",
   "hu-HU" : "yyyy. MM. dd.",
   "is-IS" : "d.M.yyyy",
   "it-IT" : "dd/MM/yyyy",
   "ja-JP" : "yyyy/MM/dd",
   "ko-KR" : "yyyy-MM-dd",
   "nl-NL" : "d-M-yyyy",
   "nb-NO" : "dd.MM.yyyy",
   "pl-PL" : "yyyy-MM-dd",
   "pt-BR" : "d/M/yyyy",
   "ro-RO" : "dd.MM.yyyy",
   "ru-RU" : "dd.MM.yyyy",
   "hr-HR" : "d.M.yyyy",
   "sk-SK" : "d. M."
}