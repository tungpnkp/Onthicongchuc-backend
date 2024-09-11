<table>
    <thead>
    <tr>
        <th>Tên</th>
        <th>SBD</th>
        <th>SĐT</th>
        <th>Email</th>
        <th>Loại tài khoản</th>
        <th>Ngày kết thúc</th>
        <th>Trạng thái</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <?php
        $text = "Không xác định";
        if (!empty($user->type)) {
            $type = json_decode($user->type, true);
            $text = "|";
            if (in_array(1, $type)) {
                $text .= "Tiếng Anh";
            }
            if (in_array(2, $type)) {
                $text .= "| KTC ngành thuế";
            }
            if (in_array(3, $type)) {
                $text .= "| KTC kho bạc nhà nước";
            }
            if (in_array(4, $type)) {
                $text .= "| Tất cả";
            }
        }
        ?>
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->code }}</td>
            <td>{{ $user->phone }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $text }}</td>
            <td>{{ $user->created_at->addYear(1)->format("d/m/Y") }}</td>
            <td>{{ $user->status == 1 ? "Hoạt động" : "Tạm dừng" }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
