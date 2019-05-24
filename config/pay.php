<?php
 return [
	//应用ID,您的APPID。
	   'app_id' => "2016092700609224",
	   'seller_id'=>'2088102177524838',

	   //商户私钥
	   'merchant_private_key' => "MIIEowIBAAKCAQEAtSyJFb7sLYMzFLKaEmTioE1tc5Wyqq1hinN8OO2ZZ3ISzmPwi5vM7w02Sf/DXflOhee7if7rnynwtnBGqiaztyXG3JDhfYYRJI11weyriwrpYiiZSMg4rJ+Vv/abdlH+tUb7AKEpS108eKEMH3LOSGGkrcM6Zm6iRGqj8EAbMexmQbL9k0L07j0LNRvgoMTjrgjaMOCsorP4RUIc+258ZdusCKDwRQcgHlun8oEaMViLDsrbdjmJGhjPDfXCNanJ0tnUC+1Gbqzfm3kBKAh9edYMna9N7rntyhz2z1TFm6zxFY7txYjYVQzWzUPv6yHS5Xno8Y+do3fUcp1jDc6NwQIDAQABAoIBAE+snzit9sVpHkyq4rDWJwfiJREgsyr2BqBRez651q20b+rN7pom0TALumU+khdArTScHVBpiAs98Xr500m+rWgMt/CF0nRWQjG1+CRpRWNnUA131zk3vBzGLZ/2/pVBJ+dmqLm1Jijbft15zZAC+A7Cyh4Ubhlf7AJ0d1YPiyYfl8XgJjuw7mhERiR9EtJCd3e+o0CtEAlp6C+G3tlP8CBvBv8lNg6n17F1Dyprm0xqTW0byhQ57r4XydCAf8GFsLrKUWcOIcOIVRjjhGLSeXVbqbfUZjEh2pBWekd3t7j+KM0do5ZX8PK7hmHZE3Y/v6SmX4fHNGiKceMbleWRsnECgYEA2dO+W1WhcKvAgRuQ8uArvmuw41mEosadFGW1kh2xu3exNySBk0YkKdjCaYKZwtVOYnz2tZQvbxhzVgsIHPu0+CGJ2faSHlTv5Oz5XcRUKnfMWU9aceR0isEw9UgI7IDFVv+WhCqbvURdjqNcrYX1JnmUkYMjrQZEd5i1M2eHwt0CgYEA1OxxHGSh059Pfjwn0OY4uHKeW3wtw4G87sAWRREI/xGB8c2M6slqU605GFqdU5xnuiqfPh94WbFu+JZCEgknAuoo0AfgcFgcEZimujAHGxg75O16A7RJ9IJhL6fFOXtZ2WyB/q3JqY0EOS9DpsXASEud9fsl5oIVfMLDm7q1rjUCgYB3cd3jNYyGPRgdc+FC3Vv/WgwwTuklXSXLho5PJktMkjCyHSmUeZThAYRx2PS52r/Vz9J09WIhWFX+a6QySHAO1E1EISo0ybyWWr/PBBZnBzQeER52b6DcaV2QUsTZ8y7DKvn9VWP7IB7xVXE/kcJBK9AlDrUkbthigHF0U6QlXQKBgGmCHG6gYxw1VjPMZUJ7wmGL0nKs7xRxBokcQCzLSzTxnP2QCKMTWY/LR36M8GbNGw1CL5SeDYoMhVvDQUwkouMr2ztym8Gvt4D0rrGWOP8xHATNtGd/gCIcNUJjFTTtrdEylpv3y/UrfotDo3dRiH2KkuSgJeC6VyFydXGGrJ59AoGBANd6GsjG5DqlWB/6KiSqs0BzyxBbOb+1pO3R7TqBNcgzg+w59ogaOUzafT79MT/N0awXRcq4jyeTXbKcilMOkPYZ0Hm2+kJQMHq8f5Yvx7B347GZruhvUmd3Wz4nNJHUl/OZurQxiWYwMaeDEtflnTnVLcLJVsQyopOxB+mzMwHt",
	   
	   //异步通知地址
	   'notify_url' => "http://www.probject.com/order/nofitipay",
	   
	   //同步跳转
	   'return_url' => "http://www.probject.com/order/returnUrl",

	   //编码格式
	   'charset' => "UTF-8",

	   //签名方式
	   'sign_type'=>"RSA2",

	   //支付宝网关
	   'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

	   //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
	   'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5W8IH5pUc/4SSxtPUUgNRRLOi6OZgeMYhpxyFfobDeZcQkHdXVR6LjWySIjUggo/iH4EsKhQBpzYwQ9b80vQSgK1sOGxnlTPIYJVzF5alNN5vnfDscLp+ijJuf/6YugfQLfNqAX1V6/Hdy2ljHDV0F64NCkv8aea8nlFx4G4KwyJLhEHetrBBRQY33/nQk1Z6MDSfXAMojmMyUaCPwxDgT2XV3h+lB/UTByF0T2Gk37Hxge/hGEZMHfsBrXqOX1YHGWUSGuqzOH4n/+nqRRQ3+jIHO2aFXt6q5vPp1iORDfJ0/HSjPAMytry9OJMacfC4eTi1kxFgvSF+YVuK+Pa1wIDAQAB",
	   ];
?>