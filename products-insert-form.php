
<style>
    * {
        font-family: peyda;
    }

    body {
        direction: rtl;
        font-family: peyda;
        background-color: #a2d8eb;

    }

    .glass-card {
        background: var(--color-card-bg);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        box-shadow: 0 4px 6px var(--color-shadow);
    }

    .sidebar {
        transition: all 0.3s ease;
    }

    .sidebar-item:hover {
        background-color: var(--color-hover-bg);
    }

    .product-table tr:nth-child(even) {
        background-color: var(--color-even-row);
    }

    .product-table tr:hover {
        background-color: var(--color-hover-row);
    }

    .form-input {
        transition: all 0.3s ease;
    }

    .form-input:focus {
        box-shadow: 0 0 0 3px var(--color-input-focus);
    }

    #date-container {
        margin-left: 30px;
        color: var(--color-text);
    }


</style>
<link rel="stylesheet" href="color.css">
<body >
    <div class="flex flex-col md:flex-row h-screen overflow-hidden">
        <!-- side bar-->
        <?php include("sidebar.php"); ?>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto relative">
            <div id="overlay" class="hidden fixed inset-0 z-30 bg-black bg-opacity-50 md:hidden"></div>
            <!-- Top Navigation -->
            <?php include("header.php"); ?>

            <!-- Add Product Form -->
            <div class="glass-card p-6 bg-white shadow-sm  rounded-xl m-6">
                <h3 class="font-semibold text-gray-800 mb-6 text-lg border-b pb-3">ثبت محصول جدید</h3>
                <form method="post" action="add.php" class="grid gap-6" id="addProductForm">
                    <!-- فیلدها: 3 ستون در دو ردیف -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- نام محصول -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">نام محصول</label>
                            <select name="id" id="productSelect"
                                class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                                <?php
                                $conn = new mysqli("localhost", "root", "", "salam");
                                $sql = "SELECT id, product_name FROM product_prices ORDER BY product_name ASC";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row["id"] . "'>" . htmlspecialchars($row["product_name"]) . "</option>";
                                    }
                                } else {
                                    echo "<option disabled>هیچ محصولی یافت نشد</option>";
                                }
                                $conn->close();
                                ?>
                                <option value="add_new" class="text-blue-600 font-bold">+ افزودن محصول جدید</option>
                            </select>
                            <input type="text" name="new_name" id="newProductName" placeholder="نام محصول جدید"
                                class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 mt-2 hidden">
                        </div>

                        <!-- رنگ -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">رنگ</label>
                            <select name="color"
                                class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                                <option value="مشکی">مشکی</option>
                                <option value="سفید">سفید</option>
                                <option value="قرمز">قرمز</option>
                                <option value="سبز">سبز</option>
                                <option value="زرد">زرد</option>
                                <option value="خردلی">خردلی</option>
                                <option value="کرمی">کرمی</option>
                                <option value="قهوه ای">قهوه ای</option>
                                <option value="صورتی">صورتی</option>
                                <option value="زرشکی">زرشکی</option>
                                <option value="توسی">توسی</option>
                                <option value="گلبهی">گلبهی</option>
                                <option value="بنفش">بنفش</option>
                                <option value="آبی">آبی</option>
                                <option value="تعویضی">تعویضی</option>
                            </select>
                        </div>

                        <!-- سایز -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">سایز</label>
                            <select name="size"
                                class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                                <option value="36">36</option>
                                <option value="38">38</option>
                                <option value="40">40</option>
                                <option value="42">42</option>
                                <option value="44">44</option>
                                <option value="46">46</option>
                                <option value="48">48</option>
                                <option value="50">50</option>
                                <option value="52">52</option>
                                <option value="54">54</option>
                                <option value="56">56</option>
                            </select>
                        </div>

                        <!-- تاریخ -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">تاریخ</label>
                            <input type="text" name="date" id="date-input"
                                class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- قیمت -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">قیمت</label>
                            <input type="text" name="price" placeholder="قیمت"
                                class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- روش پرداخت -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">روش پرداخت</label>
                            <select name="payment_method" required
                                class="w-full form-input bg-gray-100 border-0 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                                <option value="کارتخوان">کارتخوان</option>
                                <option value="کارت به کارت">کارت به کارت</option>
                            </select>
                        </div>
                    </div>

                    <!-- دکمه ثبت: یک ردیف جدا -->
                    <div class="mt-6">
                        <button type="submit"
                            class="w-full md:w-1/3 mx-auto bg-blue-500 hover:bg-blue-600 text-white py-2.5 px-4 rounded-lg transition duration-200 flex items-center justify-center submi-btn" >
                            
                            <span>ثبت محصول</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- JS برای نمایش فیلد محصول جدید -->
            <script>
                document.getElementById('productSelect').addEventListener('change', function () {
                    var newNameInput = document.getElementById('newProductName');
                    if (this.value === 'add_new') {
                        newNameInput.classList.remove('hidden');
                    } else {
                        newNameInput.classList.add('hidden');
                    }
                });
            </script>
        </div>
    </div>
<script src="tailwind.js"></script>
<script src="scripts.js"></script>

</body>
