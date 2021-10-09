<template>
    <div class="minimal-container">
        <div class="minimal-card p-fluid">
            <div class="login-panel-content">
                <div class="grid">
                    <div class="col-12 logo-container">
                        <h2>{{ title }}</h2>
                    </div>
                    <div class="col-12">
                        <slot name="content"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'MinimalLayout',
    props: {
        title: {
            type: String,
            default: 'Tytuł'
        }
    }
};
</script>

<style>
.minimal-container{
    width: 100%;
    min-height: 100vh;
    display: -webkit-box;
    display: -webkit-flex;
    display: -moz-box;
    display: -ms-flexbox;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    padding: 15px;
    background: #f2f2f2;
}

.minimal-card{
    width: 390px;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    padding: 77px 55px 33px;
    box-shadow: 0 5px 10px 0 rgba(0, 0, 0 , 0.1);
    -moz-box-shadow: 0 5px 10px 0 rgba(0,0,0,.1);
    -webkit-box-shadow: 0 5px 10px 0 rgba(0, 0, 0 , 0.1);
    -o-box-shadow: 0 5px 10px 0 rgba(0,0,0,.1);
    -ms-box-shadow: 0 5px 10px 0 rgba(0,0,0,.1);
}
</style>
