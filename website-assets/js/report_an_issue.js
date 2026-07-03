$(document).ready(function(){
	var contactForm = $("#contact-form");
	var validator = contactForm.validate({
		rules:{
			name :{required : true},
			email : {required : true, email : true},
			phone : {digits : true, minlength: 10, maxlength: 10},
            issue_type : {required : true},
            page_url : {url: true},
			subject : {required : true},
			message : {required : true}
		},

        submitHandler: function (form) {

            if (grecaptcha.getResponse() === "") {

                alert("Please complete the reCAPTCHA.");
                return false;
            }

            form.submit();
        }
		// messages:{
		// 	name :{ required : "Pls. fill your vehicle no." },
		// 	material : { required : "Pls. fill your material details"},
		// 	party_name : { required : "This field is required" },
		// 	gross : {required : "This field is required", digits : "Please enter numbers only"},
		// 	tare : { required : "This field is required", digits : "Please enter numbers only"},
		// 	net : {required : "This field is required", digits : "Please enter numbers only"},
		// 	date_invoice : { required : "This field is required"},
		// 	charges : { required : "This field is required", digits : "Please enter numbers only"},			
		// }
	});
});