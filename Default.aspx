<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Default.aspx.cs" Inherits="_Default" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
<title>Hightechnology Ajax Auto Complete</title>
<style type="text/css">
.auto-style1 {
width: 900px;
}
</style>
</head>
<body>
<form id="form1" runat="server">
<div>
<asp:ToolkitScriptManager ID="ToolkitScriptManager1" runat="server"></asp:ToolkitScriptManager>
<table align="center" cellpadding="2" class="auto-style1">
<tr>
<td colspan="2">
<h1>Ajax Auto Complete Extender</h1>
</td>
</tr>
<tr>
<td>Country</td>
<td>
<asp:TextBox ID="TextBox1" runat="server"></asp:TextBox>
<asp:AutoCompleteExtender ID="AutoCompleteExtender1" runat="server" TargetControlID="TextBox1" ServicePath="WebService.asmx" 
MinimumPrefixLength="1" EnableCaching="true" CompletionSetCount="1" CompletionInterval="1000" ServiceMethod="Countries">
</asp:AutoCompleteExtender> 
</td>
</tr>
</table>
</div>
</form>
</body>
</html>
