$(function()
{
	$verify_win = $('#verify-win');

	// 查看密码
	$('.eye-submit').on('click', function()
	{
		var $obj = $(this).parent().prev().find('input');
		if($obj.attr('type') == 'password')
		{
			$(this).addClass('cr-green');
			$obj.attr('type', 'text');
		} else {
			$(this).removeClass('cr-green');
			$obj.attr('type', 'password');
		}
	});


	// 短信验证码获取
	$('.verify-submit, .verify-submit-win').on('click', function()
	{
		var $this = $(this);
		var $mobile = $('#mobile');
		var $verify = $('#verify-img-value');
		var verify = '';
		if($mobile.hasClass('am-field-valid'))
		{
			// 是否需要先校验图片验证码
			if($this.data('verify') == 1)
			{
				// 开启图片验证码窗口
				$verify_win.modal('open');
				$verify.focus();
				return false;
			}

			// 验证码窗口操作按钮则更新按钮对象
			var is_win = $(this).data('win');
			if(is_win == 1)
			{
				$this = $('.verify-submit');

				// 验证码参数处理
				verify = $verify.val().replace(/\s+/g, '');

				if(verify.length < 6)
				{
					Prompt($verify.data('validation-message'));
					$verify.focus();
					return false;
				}
			}

			// 验证码时间间隔
			var time_count = parseInt($this.data('time'));
			
			// 按钮交互
			$this.button('loading');
			if(is_win == 1)
			{
				$('.verify-submit-win').button('loading');
			}

			// 发送验证码
			$.ajax({
				url:$('.verify-submit').data('url'),
				type:'POST',
				data:{"mobile":$mobile.val(), "verify":verify},
				dataType:'json',
				success:function(result)
				{
					if(result.code == 0)
					{
						var intervalid = setInterval(function()
						{
							if(time_count == 0)
							{
								$this.button('reset');
								if(is_win == 1)
								{
									$('.verify-submit-win').button('reset');
								}
								$this.text($this.data('text'));
								$verify.val('');
								clearInterval(intervalid);
							} else {
								var send_msg = $this.data('send-text').replace(/{time}/, time_count--);
								$this.text(send_msg);
							}
						}, 1000);
						$verify_win.modal('close');
					} else {
						$this.button('reset');
						if(is_win == 1)
						{
							$('.verify-submit-win').button('reset');
						}
						Prompt(result.msg);
					}
				},
				error:function()
				{
					$this.button('reset');
					if(is_win == 1)
					{
						$('.verify-submit-win').button('reset');
					}
					Prompt('网络错误');
				}
			});			
		} else {
			$verify_win.modal('close');
			$mobile.focus();
		}
	});

});