function Validator() {

	this.min_length = function(string, minimum){
		string = $.trim(string);
		return string.length>=minimum;
	},
	
	this.max_length = function(string, maximum){
		
		string = $.trim(string);
		return string.length<=maximum;
	},
	
	this.length = function(string, min_length, max_length){
		
		string = $.trim(string);
		var min_length = min_length ? min_length : 1;
		var max_length = max_length ? max_length : 250;
		
		return this.min_length(string, min_length) && this.max_length(string, max_length)
		
	},
	
	this.not_empty = function(string){
		
		string = $.trim(string);
		return string!="";
	},
	
	this.alphanumeric = function(string){
		
		string = $.trim(string);
		return /^[a-zA-Z0-9_]*$/.test(string)&&string;
	},
	
	this.email = function(string){
		
		string = $.trim(string);
		return /.+@.+\..+/i.test(string)&&string;
	},
	
	this.password = function(string){
		
		string = $.trim(string);
		return /((?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20})/.test(string)&&string;
	},
	
	this.name = function(string){
		
		string = $.trim(string);
		return /^(\w+ )*\w+$/.test(string)&&string;
	},
	
	this.address = function(string){
		
		string = $.trim(string);
		//return /^(?:[a-zA-Z]+(?:[.'\-,])?\s?)+$/.test(string)&&string;
		return this.not_empty(string)&&string;
	},
	
	this.address2 = function(string){
		
		string = $.trim(string);
		//return /^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$/.test(string)&&string;
		return this.not_empty(string)&&string;
	},
	
	this.zipcode = function(string){
		
		string = $.trim(string);
		return /\d{5}-?(\d{4})?$/.test(string)&&string;
	},
	
	this.address_city = function(string){
		
		string = $.trim(string);
		//return /^(?:[a-zA-Z]+(?:[.'\-,])?\s?)+$/.test(string)&&string;
		return this.not_empty(string)&&string;
	},
	
	
	this.city = function(string){
		
		return this.address_city(string);
	},
	
	this.credit_card_number = function(string){
		
		string = $.trim(string);
		return $.payment.validateCardNumber(string);
	},
	
	this.credit_card_cvc = function(string){
		
		string = $.trim(string);
		return $.payment.validateCardCVC(string);
	},
	
	this.credit_card_expiration_month = function(string){
		
		string = $.trim(string);
		return /1[0-2]|[1-9]/.test(string)&&string;
	},
	
	this.credit_card_expiration_year = function(string){
		
		string = $.trim(string);
		return /^\d{4}$/.test(string)&&string;
	}
	
}