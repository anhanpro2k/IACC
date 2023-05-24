jQuery(document).ready(function ($) {
    $('#mona-file-upload').on('change', function (e) {
        var reader = new FileReader();
        var xlsxflag = true;
        if (typeof (FileReader) != "undefined") {
            var reader = new FileReader();
            reader.onload = function (e) {
                var data = e.target.result;
                /*Converts the excel data in to object*/
                if (xlsxflag) {
                    var workbook = XLSX.read(data, {
                        type: 'binary'
                    });
                } else {
                    var workbook = XLS.read(data, {
                        type: 'binary'
                    });
                }
                /*Gets all the sheetnames of excel in to a variable*/
                var sheet_name_list = workbook.SheetNames;

                var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/
                sheet_name_list.forEach(function (y) {
                    /*Iterate through all sheets*/
                    /*Convert the cell value to Json*/
                    if (xlsxflag) {
                        var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                    } else {
                        var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                    }
                    if (exceljson.length > 0 && cnt == 0) {
                        var $html = '';
                        var $count = 1;
                        var $countt = 0;
                        var $total = 0;
                        $.each(exceljson, function (index, item) {
                            console.log(item);
                        });

                        cnt++;
                    }
                });

            }
            if (xlsxflag) {
                /*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                reader.readAsArrayBuffer(this.files[0]);
            } else {
                reader.readAsBinaryString(this.files[0]);
            }
        }
    });
    $('#m_vn_post_provinces_user').on('change', function (e) {
        let html = `<option value="" selected="" disabled="">Chọn Quận/huyện</option>`;
        let district = $(this).find('option:selected').attr('data-district');
        if (district != '') {
            let districtArr = JSON.parse(district);
            Object.keys(districtArr).forEach(function (key) {
                html += `<option value="${key}" >${districtArr[key]}</option>`;
            })
        }
        $('#m_vn_post_district_user').html(html);

    });
    $('#m_viettel_post_provinces_user').on('change', function (e) {
        e.preventDefault();
        let value = $(this).find('option:selected').val();
        let token = $('#viettel-token').val();
        var settings = {
            "url": `https://transporter.viettelsale.com/v1/location/districts?provinceId=${value}`,
            "method": "GET",
            "timeout": 0,
            "headers": {
                "Authorization": `${token}`
            },
        };
        $.ajax(settings).done(function (response) {
            if (response.status == 200) {
                let html = `<option value="" selected disabled>Chọn Quận/huyện</option>`;

                let data = response.data;
                jQuery.each(data, function (e, i) {
                    html += `<option value="${i.DISTRICT_ID}">${i.DISTRICT_NAME}</option>`;
                });
                $('#m_viettel_post_district_user').html(html);
            }
        });
    });
});